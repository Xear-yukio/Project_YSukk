<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Cek kelengkapan profil untuk notifikasi halus
        $isProfileComplete = $user->phone && $user->province && $user->city && $user->district && $user->postal_code && $user->address;

        $cart = session()->get('cart', []);
        
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Keranjang Anda kosong!');
        }

        $total = 0;
        foreach ($cart as $item) {
            $price = (float) str_replace('.', '', $item['price']);
            $total += $price * $item['quantity'];
        }

        $formattedTotal = number_format($total, 0, ',', '.');

        return view('checkout.index', compact('cart', 'formattedTotal', 'isProfileComplete'));
    }

    public function process(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'province' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'district' => 'required|string|max:255',
            'postal_code' => 'required|string|max:10',
            'address' => 'required|string',
            'payment' => 'required|string'
        ]);

        $cart = session()->get('cart', []);
        
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Keranjang Anda kosong!');
        }

        $totalAmount = 0;
        foreach ($cart as $item) {
            $price = (float) str_replace('.', '', $item['price']);
            $totalAmount += $price * $item['quantity'];
        }

        \DB::beginTransaction();

        try {
            $order = \App\Models\Order::create([
                'user_id' => auth()->id(),
                'full_name' => $request->full_name,
                'phone' => $request->phone,
                'email' => $request->email,
                'province' => $request->province,
                'city' => $request->city,
                'district' => $request->district,
                'postal_code' => $request->postal_code,
                'address' => $request->address,
                'notes' => $request->notes,
                'total_amount' => $totalAmount,
                'payment_method' => $request->payment,
                'status' => 'pending'
            ]);

            foreach ($cart as $id => $item) {
                // Remove formatting from price if it's a string like "120.000"
                $price = (float) str_replace('.', '', $item['price']);
                $productData = \App\Models\Product::find($id);
                $order->items()->create([
                    'product_id' => $id,
                    'product_name' => $item['name'],
                    'product_image' => $productData['main_image'] ?? null,
                    'quantity' => $item['quantity'],
                    'price' => $price,
                    'total_price' => $price * $item['quantity']
                ]);
            }

            \DB::commit();
            session()->forget('cart');

            return redirect()->route('checkout.payment', $order->id);

        } catch (\Exception $e) {
            \DB::rollback();
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memproses pesanan Anda. Silakan coba lagi.');
        }
    }

    public function payment($orderId)
    {
        $order = \App\Models\Order::with('items')->findOrFail($orderId);
        
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        $paymentInfo = $this->getPaymentInstructions($order->payment_method);

        return view('checkout.payment', compact('order', 'paymentInfo'));
    }

    public function confirmPayment(Request $request, $orderId)
    {
        $order = \App\Models\Order::findOrFail($orderId);
        
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        if ($order->payment_method !== 'cod') {
            $request->validate([
                'payment_proof' => 'required|image|mimes:jpeg,png,jpg|max:2048'
            ]);

            if ($request->hasFile('payment_proof')) {
                $path = $request->file('payment_proof')->store('proofs', 'public');
                $order->update([
                    'payment_proof' => $path,
                    'status' => 'verifying'
                ]);
            }
        } else {
            // COD
            $order->update(['status' => 'processing']);
        }

        return redirect()->route('checkout.success', $order->id);
    }

    public function success($orderId)
    {
        $order = \App\Models\Order::findOrFail($orderId);
        
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        // Auto-update to processing only if COD and somehow still pending
        if ($order->status === 'pending' && $order->payment_method === 'cod') {
            $order->update(['status' => 'processing']);
        }

        return view('checkout.success', compact('order'));
    }

    private function getPaymentInstructions($method)
    {
        $instructions = [
            'qris' => [
                'title' => 'Bayar Mudah dengan QRIS',
                'description' => 'Scan kode QR di bawah menggunakan aplikasi GoPay, OVO, DANA, LinkAja, atau Mobile Banking pilihan Anda.',
                'image' => 'https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=BELANJAID-QRIS-TEST-1234',
                'steps' => [
                    'Buka aplikasi e-wallet atau mobile banking Anda.',
                    'Pilih menu "Scan" atau "Bayar".',
                    'Scan kode QR yang tampil di layar.',
                    'Pastikan nominal sesuai dengan total pesanan.',
                    'Masukkan PIN Anda dan selesai.'
                ]
            ],
            'bca' => [
                'title' => 'Transfer Bank BCA',
                'account_number' => '8732109988',
                'account_name' => 'PT Belanja Nusantara',
                'steps' => [
                    'Pilih m-Transfer > BCA Virtual Account atau Transfer Antar Rekening.',
                    'Masukkan nomor rekening di atas.',
                    'Pastikan nama penerima adalah PT Belanja Nusantara.',
                    'Masukkan PIN m-BCA Anda.',
                    'Simpan bukti transfer sebagai konfirmasi.'
                ]
            ],
            'bri' => [
                'title' => 'Transfer Bank BRI',
                'account_number' => '034101000743301',
                'account_name' => 'PT Belanja Nusantara',
                'steps' => [
                    'Pilih menu Transfer > Sesama BRI.',
                    'Masukkan nomor rekening tujuan di atas.',
                    'Konfirmasi data dan nominal pesanan Anda.',
                    'Masukkan PIN ATM/Mobile Banking.',
                    'Simpan bukti transaksi.'
                ]
            ],
            'bni' => [
                'title' => 'Transfer Bank BNI',
                'account_number' => '0988776655',
                'account_name' => 'PT Belanja Nusantara',
                'steps' => [
                    'Pilih menu Transfer > Antar Rekening BNI.',
                    'Input nomor rekening di atas.',
                    'Pastikan nominal sesuai dengan tagihan.',
                    'Lanjutkan transaksi dan masukkan PIN.',
                    'Simpan bukti pembayaran.'
                ]
            ],
            'mandiri' => [
                'title' => 'Transfer Bank Mandiri',
                'account_number' => '1370012345678',
                'account_name' => 'PT Belanja Nusantara',
                'steps' => [
                    'Pilih menu Transfer > Ke Rekening Mandiri.',
                    'Input nomor rekening di atas.',
                    'Konfirmasi nama penerima PT Belanja Nusantara.',
                    'Selesaikan pembayaran.',
                    'Simpan resi sebagai bukti bayar.'
                ]
            ],
            'seabank' => [
                'title' => 'Transfer SeaBank',
                'account_number' => '901234567812',
                'account_name' => 'PT Belanja Nusantara',
                'steps' => [
                    'Buka aplikasi SeaBank > Pilih Transfer.',
                    'Masukkan nomor rekening SeaBank di atas.',
                    'Konfirmasi jumlah transfer.',
                    'Masukkan PIN SeaBank.',
                    'Transaksi selesai.'
                ]
            ],
            'gopay' => [
                'title' => 'Transfer Saldo GoPay',
                'account_number' => '081234567890',
                'account_name' => 'Toko Belanja ID',
                'steps' => [
                    'Buka aplikasi Gojek, pilih menu Bayar/Transfer.',
                    'Masukkan nomor HP tujuan di atas.',
                    'Isi nominal sesuai dengan total tagihan.',
                    'Konfirmasi pembayaran dengan PIN GoPay.',
                    'Simpan bukti transfer.'
                ]
            ],
            'dana' => [
                'title' => 'Transfer Saldo DANA',
                'account_number' => '081234567890',
                'account_name' => 'Toko Belanja ID',
                'steps' => [
                    'Buka aplikasi DANA, pilih Kirim.',
                    'Kirim ke Teman dan masukkan nomor ponsel di atas.',
                    'Ketik jumlah pembayaran dengan benar.',
                    'Pilih Bayar dan masukkan PIN DANA.',
                    'Pesanan akan segera diproses.'
                ]
            ],
            'cod' => [
                'title' => 'Bayar di Tempat (COD)',
                'description' => 'Siapkan uang tunai pas untuk dibayarkan kepada kurir saat pesanan sampai.',
                'steps' => [
                    'Tim kami akan segera memproses pesanan Anda.',
                    'Kurir akan menghubungi Anda saat menuju lokasi.',
                    'Siapkan uang tunai sesuai total tagihan.',
                    'Berikan pembayarannya kepada kurir.',
                    'Berikan ulasan terbaik Anda.'
                ]
            ]
        ];

        return $instructions[$method] ?? ['title' => 'Instruksi Pembayaran', 'steps' => ['Silakan hubungi CS untuk bantuan pembayaran.']];
    }
}

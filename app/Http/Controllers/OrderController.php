<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Auth;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('orders.index', compact('orders'));
    }

    public function show($id)
    {
        $order = Order::with('items')->findOrFail($id);

        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        // Mock tracking data if not already set (for newly created orders)
        if (empty($order->courier_name)) {
            $this->generateMockTracking($order);
        }

        return view('orders.show', compact('order'));
    }

    private function generateMockTracking($order)
    {
        $couriers = ['Budhi Santoso', 'Siti Aminah', 'Andi Wijaya', 'Rina Pratama'];
        $locations = ['Gudang Pusat Jakarta', 'Pusat Transit Bandung', 'Hub Logistik Bekasi', 'Kantor Cabang Depok'];
        
        $order->update([
            'courier_name' => $couriers[array_rand($couriers)],
            'tracking_number' => 'BJ' . strtoupper(substr(md5($order->id . time()), 0, 10)),
            'estimated_arrival' => now()->addDays(rand(2, 4))->format('d M Y'),
            'current_location' => $locations[array_rand($locations)],
            'shipping_history' => json_encode([
                ['time' => now()->subHours(2)->format('H:i'), 'status' => 'Paket sedang diproses oleh penjual', 'location' => 'Jakarta'],
                ['time' => now()->subHour()->format('H:i'), 'status' => 'Paket telah diserahkan ke kurir', 'location' => 'Jakarta'],
                ['time' => now()->format('H:i'), 'status' => 'Paket dalam perjalanan ke Hub Transit', 'location' => 'Hub Jakarta'],
            ])
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;
use App\Models\User;
use App\Models\OrderItem;
use App\Models\Category;

class AdminController extends Controller
{
    public function users(Request $request)
    {
        $q = $request->input('q');
        $users = User::when($q, function($query, $q) {
            return $query->where('name', 'like', "%{$q}%")
                         ->orWhere('email', 'like', "%{$q}%");
        })->latest()->get();
        return view('admin.users.index', compact('users'));
    }

    public function userStore(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|in:admin,petugas,user',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'role' => $validated['role'],
        ]);

        return redirect()->back()->with('success', 'Pengguna berhasil ditambahkan!');
    }

    public function userUpdate(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|in:admin,petugas,user',
            'password' => 'nullable|string|min:8',
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->role = $validated['role'];

        if ($request->filled('password')) {
            $user->password = bcrypt($validated['password']);
        }

        $user->save();

        return redirect()->back()->with('success', 'Data pengguna berhasil diperbarui!');
    }

    public function userDelete($id)
    {
        $user = User::findOrFail($id);

        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri!');
        }

        $user->delete();

        return redirect()->back()->with('success', 'Pengguna berhasil dihapus!');
    }

    public function products(Request $request)
    {
        $q = $request->input('q');
        $products = Product::with('category')
            ->when($q, function($query, $q) {
                return $query->where('name', 'like', "%{$q}%")
                             ->orWhere('description', 'like', "%{$q}%");
            })
            ->latest()
            ->get();
        $categories = Category::all();
        return view('admin.products.index', compact('products', 'categories'));
    }

    public function productStore(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric',
            'stock' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'main_image' => 'nullable|url',
        ]);

        Product::create($validated);

        return redirect()->back()->with('success', 'Produk berhasil ditambahkan!');
    }

    public function productUpdate(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric',
            'stock' => 'required|integer|min:0',
            'old_price' => 'nullable|numeric',
            'discount' => 'nullable|string',
            'description' => 'nullable|string',
            'main_image' => 'nullable|url',
        ]);

        $product->update($validated);

        return redirect()->back()->with('success', 'Produk berhasil diperbarui!');
    }

    public function productDelete($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return redirect()->back()->with('success', 'Produk berhasil dihapus!');
    }

    public function promos()
    {
        $products = Product::all();
        return view('admin.promos.index', compact('products'));
    }

    public function promoUpdate(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        
        $validated = $request->validate([
            'old_price' => 'nullable|numeric',
            'discount' => 'nullable|string|max:10',
            'badge' => 'nullable|string|max:20',
        ]);

        $product->update($validated);

        return redirect()->back()->with('success', 'Promo produk berhasil diperbarui!');
    }

    public function categories(Request $request)
    {
        $q = $request->input('q');
        $categories = Category::when($q, function($query, $q) {
            return $query->where('name', 'like', "%{$q}%")
                         ->orWhere('slug', 'like', "%{$q}%");
        })->get()->map(function($cat) {
            $cat->product_count = count(Product::getByCategory($cat->slug));
            return $cat;
        });
        return view('admin.categories.index', compact('categories'));
    }

    public function categoryStore(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:categories',
            'icon' => 'nullable|string|max:255',
        ]);

        Category::create($validated);

        return redirect()->back()->with('success', 'Kategori berhasil ditambahkan!');
    }

    public function categoryUpdate(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:categories,slug,' . $category->id,
            'icon' => 'nullable|string|max:255',
        ]);

        $category->update($validated);

        return redirect()->back()->with('success', 'Kategori berhasil diperbarui!');
    }

    public function categoryDelete($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return redirect()->back()->with('success', 'Kategori berhasil dihapus!');
    }

    public function orders(Request $request)
    {
        $q = $request->input('q');
        $statusFilter = $request->input('status');
        
        $orders = Order::when($q, function($query, $q) {
            return $query->where('full_name', 'like', "%{$q}%")
                         ->orWhere('email', 'like', "%{$q}%")
                         ->orWhere('id', 'like', "%{$q}%");
        })->when($statusFilter, function($query, $status) {
            return $query->where('status', $status);
        })->latest()->get();
        
        return view('admin.orders.index', compact('orders'));
    }

    public function orderDetail($id)
    {
        $order = Order::with('items')->findOrFail($id);
        return view('admin.orders.show', compact('order'));
    }

    public function updateOrderStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $status = $request->input('status');

        if (!in_array($status, ['pending', 'verifying', 'processing', 'shipped', 'success', 'cancelled'])) {
            return redirect()->back()->with('error', 'Status tidak valid!');
        }

        $statusLabels = [
            'pending' => 'Menunggu',
            'verifying' => 'Verifikasi',
            'processing' => 'Diproses',
            'shipped' => 'Sedang Dikirim',
            'success' => 'Selesai',
            'cancelled' => 'Dibatalkan',
        ];

        $order->status = $status;
        $order->save();

        $label = $statusLabels[$status] ?? strtoupper($status);
        return redirect()->back()->with('success', 'Status pesanan #ORD-' . str_pad($order->id, 4, '0', STR_PAD_LEFT) . ' berhasil diperbarui ke "' . $label . '"!');
    }

    public function reports()
    {
        // Fetch successful orders
        $successfulOrders = Order::where('status', 'success')->get();
        
        $totalRevenue = $successfulOrders->sum('total_amount');
        $orderCount = $successfulOrders->count();
        $averageOrderValue = $orderCount > 0 ? $totalRevenue / $orderCount : 0;

        // Aggregate by category
        $categoryStats = [];
        $allProducts = collect(Product::all());

        $successfulOrderItems = OrderItem::whereHas('order', function($q) {
            $q->where('status', 'success');
        })->get();

        foreach ($successfulOrderItems as $item) {
            $product = $allProducts->get($item->product_id);
            if ($product) {
                // Get category from breadcrumbs (usually the second item after 'Home')
                $category = $product['breadcrumbs'][1] ?? 'Lainnya';
                
                if (!isset($categoryStats[$category])) {
                    $categoryStats[$category] = [
                        'count' => 0,
                        'revenue' => 0
                    ];
                }
                
                $categoryStats[$category]['count'] += $item->quantity;
                $categoryStats[$category]['revenue'] += $item->total_price;
            }
        }

        // Format for view
        $formattedReports = [];
        foreach ($categoryStats as $cat => $stats) {
            $formattedReports[] = [
                'cat' => $cat,
                'orders' => $stats['count'],
                'revenue' => $stats['revenue'],
                // Mocking trend for visual
                'status' => $stats['revenue'] > 1000000 ? 'Meningkat' : 'Stabil'
            ];
        }

        return view('admin.reports.index', compact(
            'totalRevenue',
            'averageOrderValue',
            'formattedReports'
        ));
    }

    public function promoProducts()
    {
        $products = Product::getPromo();
        return view('products.category', [
            'title' => 'Promo Spesial',
            'products' => $products
        ]);
    }

    public function settings()
    {
        return view('admin.settings.index');
    }
}

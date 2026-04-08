<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use App\Models\OrderItem;
use App\Models\Category;

class DashboardController extends Controller
{
    /**
     * Admin Dashboard.
     */
    public function adminIndex()
    {
        $totalUsers = User::count();
        $newOrdersCount = Order::whereIn('status', ['pending', 'processing'])->count();
        $totalRevenue = Order::where('status', 'success')->sum('total_amount');
        $totalProductsSold = Order::where('status', 'success')->join('order_items', 'orders.id', '=', 'order_items.order_id')->sum('order_items.quantity');
        
        $recentOrders = Order::latest()->take(5)->get();
        
        // Dynamic Category Stats
        $successfulOrderItems = OrderItem::whereHas('order', function($q) {
            $q->where('status', 'success');
        })->with('product.category')->get();

        $catStats = [];
        foreach ($successfulOrderItems as $item) {
            if ($item->product && $item->product->category) {
                $catName = $item->product->category->name;
                $catStats[$catName] = ($catStats[$catName] ?? 0) + $item->quantity;
            }
        }

        arsort($catStats);
        $totalSoldItems = array_sum($catStats);
        
        $categoryStats = [];
        foreach (array_slice($catStats, 0, 3) as $name => $count) {
            $categoryStats[] = [
                'name' => $name,
                'percentage' => $totalSoldItems > 0 ? round(($count / $totalSoldItems) * 100) : 0,
                'color' => '#e74c3c' // Default, will alternate in view
            ];
        }

        // Add some default colors if we have them
        $colors = ['#e74c3c', '#3498db', '#2ecc71'];
        foreach ($categoryStats as $i => &$stat) {
            $stat['color'] = $colors[$i] ?? '#9b59b6';
        }

        return view('admin.dashboard', compact(
            'totalUsers', 
            'newOrdersCount', 
            'totalRevenue', 
            'totalProductsSold',
            'recentOrders',
            'categoryStats'
        ));
    }

    /**
     * Petugas Dashboard.
     */
    public function petugasIndex()
    {
        $activeProductsCount = Product::count();
        $lowStockProducts = Product::where('stock', '<', 10)->latest()->take(5)->get();
        $lowStockCount = Product::where('stock', '<', 10)->count();
        $pendingOrdersCount = Order::where('status', 'pending')->count();
        $processingOrdersCount = Order::where('status', 'processing')->count();
        
        $recentActivities = Order::latest()->take(8)->get();

        return view('petugas.dashboard', compact(
            'activeProductsCount',
            'lowStockCount',
            'lowStockProducts',
            'pendingOrdersCount',
            'processingOrdersCount',
            'recentActivities'
        ));
    }
}

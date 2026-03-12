<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Review;

class DashboardController extends Controller
{
    public function index()
    {
        // Статистика для дашборда
        $totalProducts = Product::count();
        $totalOrders = Order::count();
        $totalUsers = User::count();
        $totalRevenue = Order::where('status', 'completed')->sum('total_amount');
        $pendingReviews = Review::where('is_approved', false)->count();
        
        // Последние заказы
        $recentOrders = Order::with('user')
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();
        
        // Товары с низким остатком
        $lowStockProducts = Product::where('stock', '<', 5)
            ->where('stock', '>', 0)
            ->limit(5)
            ->get();
        
        return view('admin.dashboard', compact(
            'totalProducts',
            'totalOrders', 
            'totalUsers',
            'totalRevenue',
            'recentOrders',
            'lowStockProducts',
            'pendingReviews'
        ));
    }
}
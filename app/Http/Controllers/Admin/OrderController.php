<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Список всех заказов
     */
    public function index(Request $request)
    {
        $query = Order::with('user')->orderByDesc('created_at');
        
        // Поиск по номеру заказа или email
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%");
            });
        }
        
        // Фильтр по статусу
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }
        
        // Фильтр по дате
        if ($request->has('date') && $request->date != '') {
            $today = now()->startOfDay();
            
            switch ($request->date) {
                case 'today':
                    $query->whereDate('created_at', $today);
                    break;
                case 'week':
                    $query->whereBetween('created_at', [$today->copy()->subDays(7), now()]);
                    break;
                case 'month':
                    $query->whereBetween('created_at', [$today->copy()->subMonth(), now()]);
                    break;
            }
        }
        
        $orders = $query->paginate(15);
        
        return view('admin.orders.index', compact('orders'));
    }
    
    /**
     * Просмотр деталей заказа
     */
    public function show(Order $order)
    {
        $order->load('items');
        return view('admin.orders.show', compact('order'));
    }
    
    /**
     * Обновление статуса заказа
     */
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:new,processing,completed,cancelled'
        ]);
        
        $order->update([
            'status' => $request->status
        ]);
        
        return back()->with('success', 'Статус заказа обновлен');
    }
}
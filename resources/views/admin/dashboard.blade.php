@extends('layouts.admin')

@section('title', 'Дашборд - Админ-панель')
@section('header', 'Дашборд')

@section('content')
<div class="dashboard">
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-value">{{ $totalProducts ?? 0 }}</div>
            <div class="stat-label">Товаров</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-value">{{ $totalOrders ?? 0 }}</div>
            <div class="stat-label">Заказов</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-value">{{ $totalUsers ?? 0 }}</div>
            <div class="stat-label">Пользователей</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-value">{{ number_format($totalRevenue ?? 0, 0, '.', ' ') }} ₽</div>
            <div class="stat-label">Выручка</div>
        </div>
    </div>
    
    <div class="dashboard-card">
    <h2>Последние заказы</h2>
    <table class="admin-table">
        <thead>
            <tr>
                <th>№ заказа</th>
                <th>Клиент</th>
                <th>Сумма</th>
                <th>Статус</th>
                <th>Дата</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse($recentOrders ?? [] as $order)
                <tr>
                    <td>#{{ $order->order_number }}</td>
                    <td>{{ $order->first_name }} {{ $order->last_name }}</td>
                    <td>{{ number_format($order->total_amount, 0, '.', ' ') }} ₽</td>
                    <td>
                        <form method="POST" action="{{ route('admin.orders.status', $order) }}" id="status-form-{{ $order->id }}">
                            @csrf
                            @method('PATCH')
                            <select name="status" onchange="document.getElementById('status-form-{{ $order->id }}').submit()" 
                                    class="status-select status-{{ $order->status }}">
                                <option value="new" {{ $order->status == 'new' ? 'selected' : '' }}>Новый</option>
                                <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>В обработке</option>
                                <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Выполнен</option>
                                <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Отменен</option>
                            </select>
                        </form>
                    </td>
                    <td>{{ $order->created_at->format('d.m.Y') }}</td>
                    <td>
                        <a href="{{ route('admin.orders.show', $order) }}" class="btn-muted">Просмотр</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">Нет заказов</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
</div>
@endsection
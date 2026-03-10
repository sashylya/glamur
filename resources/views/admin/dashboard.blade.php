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
    
    <div class="dashboard-row">
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
                                <span class="status-badge status-{{ $order->status }}">
                                    {{ $order->status }}
                                </span>
                            </td>
                            <td>{{ $order->created_at->format('d.m.Y') }}</td>
                            <td>
                                <a href="{{ route('admin.orders.index') }}?order={{ $order->id }}" class="btn-muted">Просмотр</a>
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
        
        <div class="dashboard-card">
            <h2>Товары с низким остатком</h2>
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Товар</th>
                        <th>Остаток</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($lowStockProducts ?? [] as $product)
                        <tr>
                            <td>{{ $product->name }}</td>
                            <td><span class="stock-low">{{ $product->stock }}</span></td>
                            <td>
                                <a href="{{ route('admin.products.edit', $product) }}" class="btn-muted">Изменить</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3">Нет товаров с низким остатком</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
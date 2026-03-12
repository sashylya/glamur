@extends('layouts.admin')

@section('title', 'Заказы - Админ-панель')
@section('header', 'Управление заказами')

@section('content')
<div class="admin-filters">
    <form method="GET" action="{{ route('admin.orders.index') }}" class="filters-form">
        <input type="text" name="search" placeholder="Поиск по номеру или email..." value="{{ request('search') }}">
        
        <select name="status">
            <option value="">Все статусы</option>
            <option value="new" {{ request('status') == 'new' ? 'selected' : '' }}>Новые</option>
            <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>В обработке</option>
            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Выполненные</option>
            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Отмененные</option>
        </select>
        
        <select name="date">
            <option value="">За все время</option>
            <option value="today" {{ request('date') == 'today' ? 'selected' : '' }}>Сегодня</option>
            <option value="week" {{ request('date') == 'week' ? 'selected' : '' }}>За неделю</option>
            <option value="month" {{ request('date') == 'month' ? 'selected' : '' }}>За месяц</option>
        </select>
        
        <button type="submit" class="btn-muted">Фильтровать</button>
        <a href="{{ route('admin.orders.index') }}" class="btn-muted">Сбросить</a>
    </form>
</div>

<!-- Таблица заказов -->
<div style="background: #1d1e27; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); overflow-x: auto;">
    <table style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr style="background: #2b2d39; border-bottom: 2px solid #e0e0e0;">
                <th style="padding: 15px; text-align: left; color: #666; font-weight: 500;">№ заказа</th>
                <th style="padding: 15px; text-align: left; color: #666; font-weight: 500;">Дата</th>
                <th style="padding: 15px; text-align: left; color: #666; font-weight: 500;">Клиент</th>
                <th style="padding: 15px; text-align: left; color: #666; font-weight: 500;">Сумма</th>
                <th style="padding: 15px; text-align: left; color: #666; font-weight: 500;">Статус</th>
                <th style="padding: 15px; text-align: left; color: #666; font-weight: 500;">Действия</th>
            </tr>
        </thead>
        <tbody>
            @forelse($orders as $order)
                <tr style="border-bottom: 1px solid #f0f0f0;">
                    <td style="padding: 15px; font-weight: 500;">#{{ $order->order_number }}</td>
                    <td style="padding: 15px; color: #666;">{{ $order->created_at->format('d.m.Y H:i') }}</td>
                    <td style="padding: 15px;">
                        <div style="font-weight: 500;">{{ $order->first_name }} {{ $order->last_name }}</div>
                        <div style="font-size: 12px; color: #999;">{{ $order->email }}</div>
                    </td>
                    <td style="padding: 15px; font-weight: 500;">{{ number_format($order->total_amount, 0, '.', ' ') }} ₽</td>
                    <td style="padding: 15px;">
                        <form method="POST" action="{{ route('admin.orders.status', $order) }}" id="status-form-{{ $order->id }}">
                            @csrf
                            @method('PATCH')
                            <select name="status" onchange="document.getElementById('status-form-{{ $order->id }}').submit()" 
                                    style="padding: 6px 12px; border-radius: 20px; border: 1px solid #e0e0e0; font-size: 13px;
                                        @if($order->status == 'completed') background: #d4edda; color: #155724;
                                        @elseif($order->status == 'processing') background: #fff3cd; color: #856404;
                                        @elseif($order->status == 'new') background: #cce5ff; color: #004085;
                                        @else background: #f8d7da; color: #721c24; @endif">
                                <option value="new" {{ $order->status == 'new' ? 'selected' : '' }}>Новый</option>
                                <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>В обработке</option>
                                <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Выполнен</option>
                                <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Отменен</option>
                            </select>
                        </form>
                    </td>
                    <td style="padding: 15px;">
                        <a href="{{ route('admin.orders.show', $order) }}" style="color: #667eea; text-decoration: none; font-size: 13px;">Просмотр</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="padding: 40px; text-align: center; color: #999;">Заказы не найдены</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
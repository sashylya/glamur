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

<table class="admin-table">
    <thead>
        <tr>
            <th>№ заказа</th>
            <th>Дата</th>
            <th>Клиент</th>
            <th>Сумма</th>
            <th>Статус</th>
            <th>Действия</th>
        </tr>
    </thead>
    <tbody>
        @forelse($orders as $order)
            <tr>
                <td>#{{ $order->order_number }}</td>
                <td>{{ $order->created_at->format('d.m.Y H:i') }}</td>
                <td>
                    {{ $order->first_name }} {{ $order->last_name }}<br>
                    <small>{{ $order->email }}</small>
                </td>
                <td>{{ number_format($order->total_amount, 0, '.', ' ') }} ₽</td>
                <td>
                    <form method="POST" action="{{ route('admin.orders.status', $order) }}" class="status-form">
                        @csrf
                        @method('PATCH')
                        <select name="status" onchange="this.form.submit()" class="status-select status-{{ $order->status }}">
                            <option value="new" {{ $order->status == 'new' ? 'selected' : '' }}>Новый</option>
                            <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>В обработке</option>
                            <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Выполнен</option>
                            <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Отменен</option>
                        </select>
                    </form>
                </td>
                <td class="actions">
                    <a href="{{ route('admin.orders.show', $order) }}" class="btn-muted">👁️ Просмотр</a>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6">Заказы не найдены</td>
            </tr>
        @endforelse
    </tbody>
</table>

<div class="pagination">
    {{ $orders->links() }}
</div>
@endsection
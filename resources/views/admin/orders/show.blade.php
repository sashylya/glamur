@extends('layouts.admin')

@section('title', 'Заказ #' . $order->order_number)
@section('header', 'Детали заказа #' . $order->order_number)

@section('content')
<div class="order-actions">
    <a href="{{ route('admin.orders.index') }}" class="btn-muted">← Назад к заказам</a>
    
    <form method="POST" action="{{ route('admin.orders.status', $order) }}" class="inline-form">
        @csrf
        @method('PATCH')
        <select name="status" onchange="this.form.submit()" class="status-select status-{{ $order->status }}">
            <option value="new" {{ $order->status == 'new' ? 'selected' : '' }}>Новый</option>
            <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>В обработке</option>
            <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Выполнен</option>
            <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Отменен</option>
        </select>
    </form>
</div>

<div class="order-details-grid">
    <div class="detail-card">
        <h3>Информация о заказе</h3>
        <table class="details-table">
            <tr>
                <th>Номер заказа:</th>
                <td>{{ $order->order_number }}</td>
            </tr>
            <tr>
                <th>Дата создания:</th>
                <td>{{ $order->created_at->format('d.m.Y H:i') }}</td>
            </tr>
            <tr>
                <th>Статус:</th>
                <td>
                    <span class="status-badge status-{{ $order->status }}">
                        {{ $order->status }}
                    </span>
                </td>
            </tr>
            <tr>
                <th>Общая сумма:</th>
                <td><strong>{{ number_format($order->total_amount, 0, '.', ' ') }} ₽</strong></td>
            </tr>
        </table>
    </div>
    
    <div class="detail-card">
        <h3>Информация о клиенте</h3>
        <table class="details-table">
            <tr>
                <th>Имя:</th>
                <td>{{ $order->first_name }} {{ $order->last_name }}</td>
            </tr>
            <tr>
                <th>Email:</th>
                <td><a href="mailto:{{ $order->email }}">{{ $order->email }}</a></td>
            </tr>
            <tr>
                <th>Телефон:</th>
                <td><a href="tel:{{ $order->phone }}">{{ $order->phone }}</a></td>
            </tr>
        </table>
    </div>
    
    <div class="detail-card">
        <h3>Адрес доставки</h3>
        <table class="details-table">
            <tr>
                <th>Адрес:</th>
                <td>{{ $order->address }}</td>
            </tr>
            <tr>
                <th>Город:</th>
                <td>{{ $order->city }}</td>
            </tr>
            <tr>
                <th>Индекс:</th>
                <td>{{ $order->postal_code ?? '—' }}</td>
            </tr>
        </table>
    </div>
    
    @if($order->notes)
    <div class="detail-card">
        <h3>Примечание к заказу</h3>
        <p>{{ $order->notes }}</p>
    </div>
    @endif
</div>

<div class="order-items-card">
    <h3>Состав заказа</h3>
    
    <table class="items-table">
        <thead>
            <tr>
                <th>Товар</th>
                <th>Артикул</th>
                <th>Цена</th>
                <th>Количество</th>
                <th>Сумма</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
                <tr>
                    <td>{{ $item->product_name }}</td>
                    <td>{{ $item->product->sku ?? '—' }}</td>
                    <td>{{ number_format($item->price, 0, '.', ' ') }} ₽</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ number_format($item->price * $item->quantity, 0, '.', ' ') }} ₽</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="4">Итого:</th>
                <th>{{ number_format($order->total_amount, 0, '.', ' ') }} ₽</th>
            </tr>
        </tfoot>
    </table>
</div>
@endsection
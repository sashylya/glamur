@extends('layouts.app')

@section('title', 'Заказ #' . $order->order_number . ' - Гламур')

@section('content')
<div class="profile-layout">
    <div class="profile-header">
        <h1>Заказ #{{ $order->order_number }}</h1>
        <a href="{{ route('profile.orders') }}" class="btn-muted">← Назад к заказам</a>
    </div>
    
    <div class="order-details">
        <div class="order-info-grid">
            <div class="info-block">
                <h3>Статус заказа</h3>
                <p class="order-status">
                    @switch($order->status)
                        @case('new')
                            <span class="status-new">Новый</span>
                            @break
                        @case('processing')
                            <span class="status-processing">В обработке</span>
                            @break
                        @case('completed')
                            <span class="status-completed">Выполнен</span>
                            @break
                        @case('cancelled')
                            <span class="status-cancelled">Отменен</span>
                            @break
                    @endswitch
                </p>
                <p>Дата заказа: {{ $order->created_at->format('d.m.Y H:i') }}</p>
            </div>
            
            <div class="info-block">
                <h3>Получатель</h3>
                <p>{{ $order->first_name }} {{ $order->last_name }}</p>
                <p>Email: {{ $order->email }}</p>
                <p>Телефон: {{ $order->phone }}</p>
            </div>
            
            <div class="info-block">
                <h3>Доставка</h3>
                <p>{{ $order->address }}</p>
                <p>{{ $order->city }}, {{ $order->postal_code }}</p>
            </div>
            
            @if($order->notes)
            <div class="info-block">
                <h3>Примечание</h3>
                <p>{{ $order->notes }}</p>
            </div>
            @endif
        </div>
        
        <div class="order-items">
            <h3>Состав заказа</h3>
            
            <table class="order-items-table">
                <thead>
                    <tr>
                        <th>Товар</th>
                        <th>Количество</th>
                        <th>Цена</th>
                        <th>Сумма</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                        <tr>
                            <td>{{ $item->product_name }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>{{ number_format($item->price, 0, '.', ' ') }} руб.</td>
                            <td>{{ number_format($item->price * $item->quantity, 0, '.', ' ') }} руб.</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="3">Итого:</th>
                        <th>{{ number_format($order->total_amount, 0, '.', ' ') }} руб.</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection
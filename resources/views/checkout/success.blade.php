@extends('layouts.app')

@section('title', 'Заказ оформлен - Гламур')

@section('content')
<div class="order-success">
    <h1>Спасибо за заказ!</h1>
    
    <div class="success-message">
        <p>Ваш заказ №{{ $order->order_number }} успешно оформлен.</p>
        <p>Мы отправили подтверждение на ваш email: <strong>{{ $order->email }}</strong></p>
    </div>
    
    <div class="order-details">
        <h2>Детали заказа</h2>
        
        <table class="order-table">
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
        
        <div class="delivery-info">
            <h3>Доставка</h3>
            <p>
                {{ $order->address }}<br>
                {{ $order->city }}, {{ $order->postal_code }}
            </p>
        </div>
    </div>
    
    <div class="success-actions">
        <a href="{{ route('catalog.index') }}" class="btn">Продолжить покупки</a>
        
        @auth
            <a href="{{ route('profile.orders') }}" class="btn-muted">История заказов</a>
        @endauth
    </div>
</div>
@endsection
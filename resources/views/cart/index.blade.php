@extends('layouts.app')

@section('title', 'Корзина - Гламур')

@section('content')
<h1>Корзина</h1>

@if($cart && $cart->items->isNotEmpty())
    <div class="cart-layout">
        <div class="cart-items">
            @foreach($cart->items as $item)
                <div class="cart-item">
                    <img src="{{ asset($item->product->mainImage()) }}" alt="{{ $item->product->name }}" class="cart-item-image">
                    
                    <div class="cart-item-info">
                        <h3><a href="{{ route('catalog.show', $item->product) }}">{{ $item->product->name }}</a></h3>
                        <p class="price">{{ number_format($item->product->price, 0, '.', ' ') }} руб.</p>
                    </div>
                    
                    <form action="{{ route('cart.update', $item) }}" method="POST" class="cart-item-quantity">
                        @csrf
                        @method('PATCH')
                        <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" max="{{ $item->product->stock }}">
                    </form>
                    
                    <div class="cart-item-total">
                        {{ number_format($item->product->price * $item->quantity, 0, '.', ' ') }} руб.
                    </div>
                    
                    <form action="{{ route('cart.remove', $item) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-muted">Удалить</button>
                    </form>
                </div>
            @endforeach
        </div>
        
        <div class="cart-summary">
            <h3>Итого</h3>
            <div class="summary-row">
                <span>Товары ({{ $cart->count }}):</span>
                <span>{{ number_format($cart->total, 0, '.', ' ') }} руб.</span>
            </div>
            <div class="summary-total">
                <strong>К оплате:</strong>
                <strong>{{ number_format($cart->total, 0, '.', ' ') }} руб.</strong>
            </div>
            
            <a href="{{ route('checkout.index') }}" class="btn">Оформить заказ</a>
            <a href="{{ route('catalog.index') }}" class="btn-muted">Продолжить покупки</a>
        </div>
    </div>
@else
    <p>Ваша корзина пуста</p>
    <a href="{{ route('catalog.index') }}" class="btn">Перейти в каталог</a>
@endif
@endsection
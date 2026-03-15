@extends('layouts.app')

@section('title', 'Корзина - Гламур')

@section('content')
<h1>Корзина</h1>

<style>
    .cart-item-quantity {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .quantity-btn {
        width: 36px;
        height: 36px;
        background: #f5f5f5;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 20px;
        font-weight: bold;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
    }

    .quantity-btn:hover {
        background: #e0e0e0;
        border-color: #999;
    }

    .quantity-btn:active {
        background: #ccc;
        transform: scale(0.95);
    }

    .quantity-display {
        font-size: 18px;
        font-weight: 500;
        min-width: 40px;
        text-align: center;
    }

    .cart-item-total {
        font-weight: bold;
        font-size: 18px;
        color: #c44d70;
    }
    
    .alert-error {
        background: #f8d7da;
        color: #721c24;
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 20px;
        border: 1px solid #f5c6cb;
    }
</style>

@if(session('error'))
    <div class="alert-error">
        {{ session('error') }}
    </div>
@endif

@php
    // Вычисляем общую сумму и количество товаров в корзине
    $cartTotal = 0;
    $cartCount = 0;
    if ($cart && $cart->items) {
        foreach ($cart->items as $item) {
            $cartTotal += $item->product->price * $item->quantity;
            $cartCount += $item->quantity;
        }
    }
@endphp

@if($cart && $cart->items->isNotEmpty())
    <div class="cart-layout">
        <div class="cart-items">
            @foreach($cart->items as $item)
                @php
                    $itemTotal = $item->product->price * $item->quantity;
                @endphp
                <div class="cart-item" data-item-id="{{ $item->id }}" data-price="{{ $item->product->price }}" data-stock="{{ $item->product->stock }}">
                    <img src="{{ asset($item->product->mainImage()) }}" alt="{{ $item->product->name }}" class="cart-item-image">
                    
                    <div class="cart-item-info">
                        <h3><a href="{{ route('catalog.show', $item->product) }}">{{ $item->product->name }}</a></h3>
                        <p class="price">{{ number_format($item->product->price, 0, '.', ' ') }} руб.</p>
                        
                        @if($item->product->stock < $item->quantity)
                            <p style="color: #dc3545; font-size: 12px; margin-top: 5px;">
                                ⚠️ Доступно только {{ $item->product->stock }} шт.
                            </p>
                        @endif
                    </div>
                    
                    <div class="cart-item-quantity">
                        <button type="button" class="quantity-btn minus" onclick="updateQuantity({{ $item->id }}, 'decrement')">-</button>
                        <span class="quantity-display" id="quantity-{{ $item->id }}">{{ $item->quantity }}</span>
                        <button type="button" class="quantity-btn plus" onclick="updateQuantity({{ $item->id }}, 'increment')">+</button>
                    </div>
                    
                    <div class="cart-item-total" id="item-total-{{ $item->id }}">
                        {{ number_format($itemTotal, 0, '.', ' ') }} руб.
                    </div>
                    
                    <form action="{{ route('cart.remove', $item) }}" method="POST" class="remove-form">
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
                <span>Товары (<span id="cart-count">{{ $cartCount }}</span>):</span>
                <span id="cart-total">{{ number_format($cartTotal, 0, '.', ' ') }} руб.</span>
            </div>
            <div class="summary-total">
                <strong>К оплате:</strong>
                <strong id="cart-total-pay">{{ number_format($cartTotal, 0, '.', ' ') }} руб.</strong>
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

@push('scripts')
<script>
// Храним цены товаров и максимальное количество
const itemPrices = {
    @foreach($cart->items as $item)
        {{ $item->id }}: {{ $item->product->price }},
    @endforeach
};

const itemMaxStock = {
    @foreach($cart->items as $item)
        {{ $item->id }}: {{ $item->product->stock }},
    @endforeach
};

// Функция обновления количества
function updateQuantity(itemId, action) {
    const quantitySpan = document.getElementById(`quantity-${itemId}`);
    let currentQuantity = parseInt(quantitySpan.textContent);
    const price = itemPrices[itemId];
    const maxStock = itemMaxStock[itemId];
    
    // Изменяем количество
    if (action === 'increment' && currentQuantity < maxStock) {
        currentQuantity++;
    } else if (action === 'decrement' && currentQuantity > 1) {
        currentQuantity--;
    } else {
        return; // Если достигнут лимит, ничего не делаем
    }
    
    // Мгновенно обновляем отображение
    quantitySpan.textContent = currentQuantity;
    
    // Обновляем сумму товара
    const itemTotal = document.getElementById(`item-total-${itemId}`);
    itemTotal.textContent = formatPrice(price * currentQuantity) + ' руб.';
    
    // Отправляем запрос на сервер (асинхронно)
    fetch(`/cart/update/${itemId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({
            quantity: currentQuantity,
            _method: 'PATCH'
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Обновляем общую сумму корзины из ответа сервера
            document.getElementById('cart-count').textContent = data.cartCount;
            document.getElementById('cart-total').textContent = formatPrice(data.cartTotal) + ' руб.';
            document.getElementById('cart-total-pay').textContent = formatPrice(data.cartTotal) + ' руб.';
        } else {
            // Если сервер не вернул данные, пересчитываем локально
            recalculateLocally();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        // Если ошибка сети, пересчитываем локально
        recalculateLocally();
    });
}

// Функция локального пересчета (резервный вариант)
function recalculateLocally() {
    let total = 0;
    let count = 0;
    
    document.querySelectorAll('.cart-item').forEach(item => {
        const itemId = item.dataset.itemId;
        const quantity = parseInt(document.getElementById(`quantity-${itemId}`).textContent);
        const price = parseFloat(item.dataset.price);
        
        total += price * quantity;
        count += quantity;
    });
    
    document.getElementById('cart-count').textContent = count;
    document.getElementById('cart-total').textContent = formatPrice(total) + ' руб.';
    document.getElementById('cart-total-pay').textContent = formatPrice(total) + ' руб.';
}

// Функция форматирования цены
function formatPrice(price) {
    return new Intl.NumberFormat('ru-RU').format(price);
}

// Обработка удаления товара без перезагрузки
document.querySelectorAll('.remove-form').forEach(form => {
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        fetch(this.action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: new FormData(this)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Удаляем элемент товара из DOM
                this.closest('.cart-item').remove();
                
                // Обновляем общую сумму
                document.getElementById('cart-count').textContent = data.cartCount;
                document.getElementById('cart-total').textContent = formatPrice(data.cartTotal) + ' руб.';
                document.getElementById('cart-total-pay').textContent = formatPrice(data.cartTotal) + ' руб.';
                
                // Если корзина пуста, перезагружаем страницу
                if (data.cartCount === 0) {
                    location.reload();
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            // Если ошибка, удаляем элемент и пересчитываем локально
            this.closest('.cart-item').remove();
            recalculateLocally();
        });
    });
});

// При загрузке страницы проверяем, что все данные корректны
document.addEventListener('DOMContentLoaded', function() {
    recalculateLocally(); // Проверяем локальный расчет при загрузке
});
</script>
@endpush
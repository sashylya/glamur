@extends('layouts.app')

@section('title', $category->name . ' - Гламур')

@section('content')
<div class="category-header">
    <h1>{{ $category->name }}</h1>
    @if($category->description)
        <p>{{ $category->description }}</p>
    @endif
</div>

<div class="grid-3">
    @forelse($products as $product)
        @php
            $inWishlist = Auth::check() && Auth::user()->wishlists()->where('product_id', $product->id)->exists();
        @endphp
        <article class="product-card {{ $inWishlist ? 'in-wishlist' : '' }}" data-product-id="{{ $product->id }}" id="product-{{ $product->id }}">
            <a href="{{ route('catalog.show', $product) }}">
                <img src="{{ asset($product->mainImage()) }}" alt="{{ $product->name }}">
            </a>
            <h3><a href="{{ route('catalog.show', $product) }}">{{ $product->name }}</a></h3>
            <p class="price">{{ number_format($product->price, 0, '.', ' ') }} руб.</p>
            
            <div class="card-actions">
                <form action="{{ route('cart.add', $product) }}" method="POST" class="cart-form">
                    @csrf
                    <input type="hidden" name="quantity" value="1">
                    <button type="submit" class="btn">В корзину</button>
                </form>
                
                @auth
                    <form action="{{ route('wishlist.add', $product) }}" method="POST" class="wishlist-form" id="wishlist-form-{{ $product->id }}">
                        @csrf
                        <button type="submit" class="wishlist-btn {{ $inWishlist ? 'active' : '' }}">
                            ♥
                        </button>
                    </form>
                @endauth
            </div>
        </article>
    @empty
        <p>В этой категории пока нет товаров</p>
    @endforelse
</div>

<div class="pagination">
    {{ $products->links() }}
</div>
@endsection

@push('styles')
<style>
.wishlist-btn {
    background: none;
    border: 2px solid #ddd;
    border-radius: 50%;
    width: 40px;
    height: 40px;
    font-size: 20px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    color: #999;
    padding: 0;
    line-height: 1;
}

.wishlist-btn:hover {
    border-color: #ff4d4d;
    color: #ff4d4d;
    transform: scale(1.1);
}

.wishlist-btn.active {
    border-color: #ff4d4d;
    color: #ff4d4d;
    background-color: #fff0f0;
}

.product-card {
    border: 2px solid transparent;
    transition: all 0.3s ease;
    padding: 15px;
    border-radius: 8px;
    position: relative;
}

.product-card.in-wishlist {
    border-color: #ff4d4d;
    background-color: #fff9f9;
    box-shadow: 0 0 15px rgba(255, 77, 77, 0.1);
}

.card-actions {
    display: flex;
    gap: 10px;
    align-items: center;
    margin-top: 15px;
}

.card-actions .btn {
    flex: 1;
}

.wishlist-form {
    margin: 0;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Находим все формы избранного
    document.querySelectorAll('.wishlist-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const form = this;
            const button = form.querySelector('.wishlist-btn');
            const productCard = form.closest('.product-card');
            const formData = new FormData(form);
            
            // Отправляем AJAX запрос
            fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Переключаем класс active на кнопке
                    button.classList.toggle('active');
                    
                    // Переключаем класс in-wishlist на карточке товара
                    if (productCard) {
                        productCard.classList.toggle('in-wishlist');
                    }
                    
                    console.log('Успех:', data.message);
                } else {
                    console.error('Ошибка:', data.message);
                }
            })
            .catch(error => {
                console.error('Ошибка запроса:', error);
            });
        });
    });
});
</script>
@endpush
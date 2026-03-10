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
        <article class="product-card">
            <a href="{{ route('catalog.show', $product) }}">
                <img src="{{ asset($product->mainImage()) }}" alt="{{ $product->name }}">
            </a>
            <h3><a href="{{ route('catalog.show', $product) }}">{{ $product->name }}</a></h3>
            <p class="price">{{ number_format($product->price, 0, '.', ' ') }} руб.</p>
            
            <div class="card-actions">
                <form action="{{ route('cart.add', $product) }}" method="POST">
                    @csrf
                    <input type="hidden" name="quantity" value="1">
                    <button type="submit" class="btn">В корзину</button>
                </form>
                
                @auth
                    <form action="{{ route('wishlist.add', $product) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn-muted">♥</button>
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
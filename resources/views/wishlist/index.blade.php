@extends('layouts.app')

@section('title', 'Избранное - Гламур')

@section('content')
<div class="wishlist-page">
    <h1>Избранное</h1>
    
    @if(session('success'))
        <div class="notice success">{{ session('success') }}</div>
    @endif
    
    @if(session('info'))
        <div class="notice info">{{ session('info') }}</div>
    @endif

    @if($wishlist->isNotEmpty())
        <div class="grid-3">
            @foreach($wishlist as $item)
                <article class="product-card">
                    <a href="{{ route('catalog.show', $item->product) }}">
                        <img src="{{ asset($item->product->mainImage()) }}" alt="{{ $item->product->name }}">
                    </a>
                    <h3><a href="{{ route('catalog.show', $item->product) }}">{{ $item->product->name }}</a></h3>
                    <p class="price">{{ number_format($item->product->price, 0, '.', ' ') }} руб.</p>
                    
                    <div class="card-actions">
                        <form action="{{ route('cart.add', $item->product) }}" method="POST">
                            @csrf
                            <input type="hidden" name="quantity" value="1">
                            <button type="submit" class="btn">В корзину</button>
                        </form>
                        
                        <form action="{{ route('wishlist.remove', $item->product) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-muted">Удалить</button>
                        </form>
                    </div>
                </article>
            @endforeach
        </div>
    @else
        <div class="empty-wishlist">
            <p>В избранном пока нет товаров</p>
            <a href="{{ route('catalog.index') }}" class="btn">Перейти в каталог</a>
        </div>
    @endif
</div>
@endsection
@extends('layouts.app')

@section('title', 'Гламур - Главная')

@section('content')
<div class="home-shell">
    <!-- Hero секция -->
    <section class="home-hero">
        <div class="home-hero-text">
            <p class="home-eyebrow">ЧЕРНАЯ ПЯТНИЦА</p>
            <h1>ГЛАМУР</h1>
            <p>Новая коллекция украшений в темной эстетике.</p>
            <a class="btn" href="{{ route('catalog.index') }}">Смотреть каталог</a>
        </div>
        <div class="home-hero-image"></div>
    </section>

    <!-- БЛОК КАТЕГОРИЙ (4 карточки) -->
<section class="home-strip">
    @foreach($categories as $category)
        <a class="home-mini" href="{{ route('catalog.index', ['category' => $category->id]) }}" data-category="{{ $category->name }}">
            @if($category->image)
                <img src="{{ asset($category->image) }}" alt="{{ $category->name }}">
            @else
                <div class="category-placeholder">
                    <span>{{ $category->name }}</span>
                </div>
            @endif
        </a>
    @endforeach
</section>

    <!-- ПЕРВЫЙ БЛОК: баннер + 3 товара -->
    <section class="collection-section">
        <div class="collection-banner">
            <div class="banner-content">
                <h2>Новая коллекция</h2>
                <p>Темная эстетика в каждом украшении</p>
                <a href="{{ route('catalog.index', ['category' => 2, 'is_new' => 1]) }}" class="btn">Смотреть коллекцию</a>
            </div>
        </div>
        <div class="collection-products-grid">
            @foreach($collectionProducts as $product)
                <article class="product-card">
                    <div class="product-image-container" style="position: relative;">
                        @if($product->is_new)
                            <div class="badge-new" style="position: absolute; top: 10px; right: 10px; background: #667eea; color: white; padding: 4px 8px; border-radius: 4px; font-size: 11px; font-weight: bold; z-index: 10;">NEW</div>
                        @endif
                        <a href="{{ route('catalog.show', $product) }}">
                            <img src="{{ asset($product->mainImage()) }}" alt="{{ $product->name }}">
                        </a>
                    </div>
                    <h3><a href="{{ route('catalog.show', $product) }}">{{ $product->name }}</a></h3>
                    <p class="price">{{ number_format($product->price, 0, '.', ' ') }} руб.</p>
                    <div class="card-actions">
                        <form action="{{ route('cart.add', $product) }}" method="POST">
                            @csrf
                            <input type="hidden" name="quantity" value="1">
                            <button type="submit" class="btn">В корзину</button>
                        </form>
                    </div>
                </article>
            @endforeach
        </div>
    </section>

    <!-- ВТОРОЙ БЛОК: баннер слева + 4 товара справа -->
    <section class="collection-grid-section">
        <div class="collection-grid-layout">
            <div class="collection-side-banner">
                <div class="side-banner-content">
                    <h3>Аксессуары</h3>
                    <h2>Дополните образ</h2>
                    <p>Цепочки, подвески и браслеты</p>
                    <a href="{{ route('catalog.index', ['category' => '3,5']) }}" class="btn">Смотреть</a>
                </div>
            </div>
            <div class="collection-grid-products">
                @foreach($gridProducts as $product)
                    <article class="product-card">
                        <div class="product-image-container" style="position: relative;">
                            @if($product->is_new)
                                <div class="badge-new" style="position: absolute; top: 10px; right: 10px; background: #667eea; color: white; padding: 4px 8px; border-radius: 4px; font-size: 11px; font-weight: bold; z-index: 10;">NEW</div>
                            @endif
                            <a href="{{ route('catalog.show', $product) }}">
                                <img src="{{ asset($product->mainImage()) }}" alt="{{ $product->name }}">
                            </a>
                        </div>
                        <h3><a href="{{ route('catalog.show', $product) }}">{{ $product->name }}</a></h3>
                        <p class="price">{{ number_format($product->price, 0, '.', ' ') }} руб.</p>
                        <div class="card-actions">
                            <form action="{{ route('cart.add', $product) }}" method="POST">
                                @csrf
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" class="btn">В корзину</button>
                            </form>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <!-- ТРЕТИЙ БЛОК: ещё один баннер + 3 товара (как первый) -->
    <section class="collection-section">
        <div class="collection-banner" style="background-image: url('/images/another-banner.jpg');">
            <div class="banner-content">
                <h2>Особая коллекция</h2>
                <p>Украшения для особых моментов</p>
                <a href="{{ route('catalog.index', ['category' => 4]) }}" class="btn">Смотреть коллекцию</a>
            </div>
        </div>
        <div class="collection-products-grid">
            @foreach($anotherCollectionProducts ?? $collectionProducts as $product)
                <article class="product-card">
                    <div class="product-image-container" style="position: relative;">
                        @if($product->is_new)
                            <div class="badge-new" style="position: absolute; top: 10px; right: 10px; background: #667eea; color: white; padding: 4px 8px; border-radius: 4px; font-size: 11px; font-weight: bold; z-index: 10;">NEW</div>
                        @endif
                        <a href="{{ route('catalog.show', $product) }}">
                            <img src="{{ asset($product->mainImage()) }}" alt="{{ $product->name }}">
                        </a>
                    </div>
                    <h3><a href="{{ route('catalog.show', $product) }}">{{ $product->name }}</a></h3>
                    <p class="price">{{ number_format($product->price, 0, '.', ' ') }} руб.</p>
                    <div class="card-actions">
                        <form action="{{ route('cart.add', $product) }}" method="POST">
                            @csrf
                            <input type="hidden" name="quantity" value="1">
                            <button type="submit" class="btn">В корзину</button>
                        </form>
                    </div>
                </article>
            @endforeach
        </div>
    </section>
</div>
@endsection
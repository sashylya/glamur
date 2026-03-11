@extends('layouts.app')

@section('title', 'Каталог украшений - Гламур')

@section('content')
<div class="catalog-layout">
    <!-- Фильтры -->
    <aside class="filters">
        <h2>Фильтры</h2>
        <form method="GET" action="{{ route('catalog.index') }}">
            <!-- ПОИСК -->
            <div class="filter-group">
                <h3>Поиск</h3>
                <input type="text" 
                       name="search" 
                       placeholder="Поиск по названию или артикулу..." 
                       value="{{ request('search') }}"
                       style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
            </div>

            <!-- Категории -->
            <div class="filter-group">
                <h3>Категории</h3>
                <select name="category">
                    <option value="">Все категории</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }} ({{ $category->products_count }})
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Цена -->
            <div class="filter-group">
                <h3>Цена</h3>
                <div class="price-row">
                    <input type="number" name="min_price" placeholder="От" value="{{ request('min_price') }}">
                    <input type="number" name="max_price" placeholder="До" value="{{ request('max_price') }}">
                </div>
            </div>

            <!-- Материал -->
            <div class="filter-group">
                <h3>Материал</h3>
                <select name="material">
                    <option value="">Все материалы</option>
                    @foreach($materials as $material)
                        <option value="{{ $material }}" {{ request('material') == $material ? 'selected' : '' }}>
                            {{ $material }}
                        </option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn">Применить</button>
            <a href="{{ route('catalog.index') }}" class="btn-muted">Сбросить</a>
        </form>
    </aside>

    <!-- Список товаров -->
    <div class="catalog-content">
        <div class="catalog-header">
            <h1>Каталог украшений</h1>
            
            @if(request('search'))
                <div style="font-size: 14px; color: #666; margin: 5px 0;">
                    Результаты поиска: "{{ request('search') }}"
                </div>
            @endif
            
            <!-- Сортировка -->
            <form method="GET" action="{{ route('catalog.index') }}" class="order-form">
                @foreach(request()->except('sort') as $key => $value)
                    @if($key != 'page')
                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                    @endif
                @endforeach
                
                <select name="sort" onchange="this.form.submit()">
                    <option value="popularity" {{ request('sort') == 'popularity' ? 'selected' : '' }}>По популярности</option>
                    <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Цена: по возрастанию</option>
                    <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Цена: по убыванию</option>
                    <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Новинки</option>
                </select>
            </form>
        </div>

        <div class="grid-3">
            @forelse($products as $product)
                <article class="product-card">
                    <a href="{{ route('catalog.show', $product) }}">
                        <img src="{{ asset($product->mainImage()) }}" alt="{{ $product->name }}">
                    </a>
                    <h3><a href="{{ route('catalog.show', $product) }}">{{ $product->name }}</a></h3>
                    <p class="price">{{ number_format($product->price, 0, '.', ' ') }} руб.</p>
                    
                    <div style="font-size: 12px; color: #999; margin-bottom: 10px;">
                        Артикул: {{ $product->sku }}
                    </div>
                    
                    <div class="card-actions">
                        @auth
                            {{-- Авторизован: кнопка добавляет в корзину --}}
                            <form action="{{ route('cart.add', $product) }}" method="POST">
                                @csrf
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" class="btn">В корзину</button>
                            </form>
                        @else
                            {{-- Не авторизован: кнопка ведет на страницу входа --}}
                            <a href="{{ route('login') }}?redirect={{ urlencode(request()->fullUrl()) }}" class="btn">Войти для покупки</a>
                        @endauth
                        
                        @auth
                            <form action="{{ route('wishlist.add', $product) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn-muted">♥</button>
                            </form>
                        @endauth
                    </div>
                </article>
            @empty
                <p>Товары не найдены</p>
                @if(request('search') || request('category') || request('min_price') || request('max_price') || request('material'))
                    <p><a href="{{ route('catalog.index') }}" class="btn-muted">Сбросить все фильтры</a></p>
                @endif
            @endforelse
        </div>
        
        
    </div>
</div>
@endsection
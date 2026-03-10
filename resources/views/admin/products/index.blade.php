@extends('layouts.admin')

@section('title', 'Товары - Админ-панель')
@section('header', 'Управление товарами')

@section('content')
<div class="admin-actions">
    <a href="{{ route('admin.products.create') }}" class="btn">➕ Добавить товар</a>
</div>

<div class="admin-filters">
    <form method="GET" action="{{ route('admin.products.index') }}" class="filters-form">
        <input type="text" name="search" placeholder="Поиск по названию..." value="{{ request('search') }}">
        
        <select name="category">
            <option value="">Все категории</option>
            @foreach($categories ?? [] as $category)
                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
        
        <select name="stock">
            <option value="">Любой остаток</option>
            <option value="in" {{ request('stock') == 'in' ? 'selected' : '' }}>В наличии</option>
            <option value="out" {{ request('stock') == 'out' ? 'selected' : '' }}>Нет в наличии</option>
            <option value="low" {{ request('stock') == 'low' ? 'selected' : '' }}>Меньше 5</option>
        </select>
        
        <button type="submit" class="btn-muted">Фильтровать</button>
        <a href="{{ route('admin.products.index') }}" class="btn-muted">Сбросить</a>
    </form>
</div>

<table class="admin-table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Изображение</th>
            <th>Название</th>
            <th>Категория</th>
            <th>Цена</th>
            <th>Остаток</th>
            <th>Популярность</th>
            <th>Новинка</th>
            <th>Действия</th>
        </tr>
    </thead>
    <tbody>
        @forelse($products as $product)
            <tr>
                <td>{{ $product->id }}</td>
                <td>
                    <img src="{{ asset($product->mainImage()) }}" alt="" class="admin-thumb">
                </td>
                <td>{{ $product->name }}</td>
                <td>{{ $product->category->name ?? 'Без категории' }}</td>
                <td>{{ number_format($product->price, 0, '.', ' ') }} ₽</td>
                <td>
                    @if($product->stock > 5)
                        <span class="stock-ok">{{ $product->stock }}</span>
                    @elseif($product->stock > 0)
                        <span class="stock-low">{{ $product->stock }}</span>
                    @else
                        <span class="stock-out">Нет</span>
                    @endif
                </td>
                <td>{{ $product->popularity }}</td>
                <td>
                    @if($product->is_new)
                        <span class="badge-new">Новинка</span>
                    @endif
                </td>
                <td class="actions">
                    <a href="{{ route('admin.products.edit', $product) }}" class="btn-muted">✏️</a>
                    <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="inline-form">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-muted" onclick="return confirm('Удалить товар?')">🗑️</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="9">Товары не найдены</td>
            </tr>
        @endforelse
    </tbody>
</table>

<div class="pagination">
    {{ $products->links() }}
</div>
@endsection
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

<!-- Таблица товаров -->
<div style="background: #1d1e27; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); overflow-x: auto;">
    <table style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr style="background: #2b2d39; border-bottom: 2px solid #e0e0e0;">
                <th style="padding: 15px; text-align: left; color: #666; font-weight: 500;">ID</th>
                <th style="padding: 15px; text-align: left; color: #666; font-weight: 500;">Фото</th>
                <th style="padding: 15px; text-align: left; color: #666; font-weight: 500;">Название</th>
                <th style="padding: 15px; text-align: left; color: #666; font-weight: 500;">Категория</th>
                <th style="padding: 15px; text-align: left; color: #666; font-weight: 500;">Цена</th>
                <th style="padding: 15px; text-align: left; color: #666; font-weight: 500;">Остаток</th>
                <th style="padding: 15px; text-align: left; color: #666; font-weight: 500;">Популярность</th>
                <th style="padding: 15px; text-align: left; color: #666; font-weight: 500;">Новинка</th>
                <th style="padding: 15px; text-align: left; color: #666; font-weight: 500;">Действия</th>
            </tr>
        </thead>
        <tbody>
            @forelse($products as $product)
                <tr style="border-bottom: 1px solid #f0f0f0;">
                    <td style="padding: 15px;">{{ $product->id }}</td>
                    <td style="padding: 15px;">
                        <img src="{{ asset($product->mainImage()) }}" alt="" style="width: 50px; height: 50px; object-fit: cover; border-radius: 6px;">
                    </td>
                    <td style="padding: 15px; font-weight: 500;">{{ $product->name }}</td>
                    <td style="padding: 15px; color: #666;">{{ $product->category->name ?? '—' }}</td>
                    <td style="padding: 15px; font-weight: 500;">{{ number_format($product->price, 0, '.', ' ') }} ₽</td>
                    <td style="padding: 15px;">
                        @if($product->stock > 5)
                            <span style="color: #28a745;">{{ $product->stock }}</span>
                        @elseif($product->stock > 0)
                            <span style="color: #ffc107;">{{ $product->stock }}</span>
                        @else
                            <span style="color: #dc3545;">Нет</span>
                        @endif
                    </td>
                    <td style="padding: 15px;">{{ $product->popularity }}</td>
                    <td style="padding: 15px;">
                        @if($product->is_new)
                            <span style="background: #667eea; color: white; padding: 4px 8px; border-radius: 4px; font-size: 11px;">NEW</span>
                        @endif
                    </td>
                    <td style="padding: 15px;">
                        <div style="display: flex; gap: 8px;">
                            <a href="{{ route('admin.products.edit', $product) }}" style="color: #667eea; text-decoration: none;">✏️</a>
                            <form action="{{ route('admin.products.destroy', $product) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="color: #dc3545; border: none; background: none; cursor: pointer;" onclick="return confirm('Удалить товар?')">🗑️</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" style="padding: 40px; text-align: center; color: #999;">Товары не найдены</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
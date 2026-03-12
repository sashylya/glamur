@extends('layouts.admin')

@section('title', 'Категории - Админ-панель')
@section('header', 'Управление категориями')

@section('content')
<div class="admin-actions">
    <a href="{{ route('admin.categories.create') }}" class="btn">➕ Добавить категорию</a>
</div>

<div style="background: #1d1e27; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); overflow-x: auto;">
    <table style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr style="background: #2b2d39; border-bottom: 2px solid #e0e0e0;">
                <th style="padding: 15px; text-align: left; color: #666; font-weight: 500;">ID</th>
                <th style="padding: 15px; text-align: left; color: #666; font-weight: 500;">Название</th>
                <th style="padding: 15px; text-align: left; color: #666; font-weight: 500;">Slug</th>
                <th style="padding: 15px; text-align: left; color: #666; font-weight: 500;">Описание</th>
                <th style="padding: 15px; text-align: left; color: #666; font-weight: 500;">Сортировка</th>
                <th style="padding: 15px; text-align: left; color: #666; font-weight: 500;">Товаров</th>
                <th style="padding: 15px; text-align: left; color: #666; font-weight: 500;">Действия</th>
            </tr>
        </thead>
        <tbody>
            @forelse($categories as $category)
                <tr style="border-bottom: 1px solid #f0f0f0;">
                    <td style="padding: 15px;">{{ $category->id }}</td>
                    <td style="padding: 15px; font-weight: 500;">{{ $category->name }}</td>
                    <td style="padding: 15px; color: #666;">{{ $category->slug }}</td>
                    <td style="padding: 15px; color: #666;">{{ Str::limit($category->description, 30) }}</td>
                    <td style="padding: 15px;">{{ $category->sort ?? 0 }}</td>
                    <td style="padding: 15px;">
                        <span style="background: #667eea20; color: #667eea; padding: 4px 12px; border-radius: 20px; font-size: 12px;">
                            {{ $category->products_count ?? 0 }}
                        </span>
                    </td>
                    <td style="padding: 15px;">
                        <div style="display: flex; gap: 8px;">
                            <a href="{{ route('admin.categories.edit', $category) }}" style="color: #667eea; text-decoration: none;">✏️</a>
                            <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="color: #dc3545; border: none; background: none; cursor: pointer;" onclick="return confirm('Удалить категорию?')">🗑️</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="padding: 40px; text-align: center; color: #999;">Категории не найдены</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
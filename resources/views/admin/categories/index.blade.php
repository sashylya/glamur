@extends('layouts.admin')

@section('title', 'Категории - Админ-панель')
@section('header', 'Управление категориями')

@section('content')
<div class="admin-actions">
    <a href="{{ route('admin.categories.create') }}" class="btn">➕ Добавить категорию</a>
</div>

<table class="admin-table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Название</th>
            <th>Slug</th>
            <th>Описание</th>
            <th>Сортировка</th>
            <th>Товаров</th>
            <th>Действия</th>
        </tr>
    </thead>
    <tbody>
        @forelse($categories as $category)
            <tr>
                <td>{{ $category->id }}</td>
                <td>{{ $category->name }}</td>
                <td>{{ $category->slug }}</td>
                <td>{{ Str::limit($category->description, 50) }}</td>
                <td>{{ $category->sort }}</td>
                <td>{{ $category->products_count ?? 0 }}</td>
                <td class="actions">
                    <a href="{{ route('admin.categories.edit', $category) }}" class="btn-muted">✏️</a>
                    <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="inline-form">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-muted" onclick="return confirm('Удалить категорию? Товары в этой категории останутся без категории.')">🗑️</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="7">Категории не найдены</td>
            </tr>
        @endforelse
    </tbody>
</table>
@endsection
@extends('layouts.admin')

@section('title', isset($category) ? 'Редактирование категории' : 'Новая категория')
@section('header', isset($category) ? 'Редактирование категории' : 'Новая категория')

@section('content')
<form method="POST" action="{{ isset($category) ? route('admin.categories.update', $category) : route('admin.categories.store') }}" 
      enctype="multipart/form-data" class="admin-form">
    @csrf
    @if(isset($category))
        @method('PUT')
    @endif
    
    <div class="form-group">
        <label for="name">Название *</label>
        <input type="text" name="name" id="name" value="{{ old('name', $category->name ?? '') }}" required>
        @error('name')
            <div class="error">{{ $message }}</div>
        @enderror
    </div>
    
    <div class="form-group">
        <label for="slug">Slug (URL) *</label>
        <input type="text" name="slug" id="slug" value="{{ old('slug', $category->slug ?? '') }}" required>
        <small>Автоматически генерируется из названия, если оставить пустым</small>
        @error('slug')
            <div class="error">{{ $message }}</div>
        @enderror
    </div>
    
    <div class="form-group">
        <label for="description">Описание</label>
        <textarea name="description" id="description" rows="4">{{ old('description', $category->description ?? '') }}</textarea>
        @error('description')
            <div class="error">{{ $message }}</div>
        @enderror
    </div>
    
    <div class="form-row">
        <div class="form-group">
            <label for="sort">Порядок сортировки</label>
            <input type="number" name="sort" id="sort" value="{{ old('sort', $category->sort ?? 0) }}" min="0">
            <small>Меньшее число = выше в списке</small>
            @error('sort')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="image">Изображение категории</label>
            <input type="file" name="image" id="image" accept="image/*">
            @error('image')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>
    </div>
    
    @if(isset($category) && $category->image)
        <div class="current-image">
            <h4>Текущее изображение</h4>
            <img src="{{ asset($category->image) }}" alt="{{ $category->name }}" class="preview-image">
        </div>
    @endif
    
    <div class="form-actions">
        <button type="submit" class="btn">Сохранить</button>
        <a href="{{ route('admin.categories.index') }}" class="btn-muted">Отмена</a>
    </div>
</form>

@push('scripts')
<script>
    document.getElementById('name').addEventListener('input', function() {
        const slug = this.value
            .toLowerCase()
            .replace(/[^\w\s-]/g, '')
            .replace(/\s+/g, '-');
        document.getElementById('slug').value = slug;
    });
</script>
@endpush
@endsection 
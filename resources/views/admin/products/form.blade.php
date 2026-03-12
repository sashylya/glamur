@extends('layouts.admin')

@section('title', isset($product) ? 'Редактирование товара' : 'Новый товар')
@section('header', isset($product) ? 'Редактирование товара' : 'Новый товар')

@section('content')
<form method="POST" action="{{ isset($product) ? route('admin.products.update', $product) : route('admin.products.store') }}" 
      enctype="multipart/form-data" class="admin-form">
    @csrf
    @if(isset($product))
        @method('PUT')
    @endif
    
    <div class="form-grid">
        <div class="form-main">
            <div class="form-group">
                <label for="name">Название *</label>
                <input type="text" name="name" id="name" value="{{ old('name', $product->name ?? '') }}" required>
                @error('name')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="form-group">
                <label for="slug">Slug (URL) *</label>
                <input type="text" name="slug" id="slug" value="{{ old('slug', $product->slug ?? '') }}" required>
                <small>Автоматически генерируется из названия, если оставить пустым</small>
                @error('slug')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="form-group">
                <label for="description">Описание *</label>
                <textarea name="description" id="description" rows="6" required>{{ old('description', $product->description ?? '') }}</textarea>
                @error('description')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="price">Цена (руб) *</label>
                    <input type="number" name="price" id="price" value="{{ old('price', $product->price ?? '') }}" step="0.01" required>
                    @error('price')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="stock">Остаток на складе *</label>
                    <input type="number" name="stock" id="stock" value="{{ old('stock', $product->stock ?? 0) }}" required>
                    @error('stock')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="sku">Артикул (SKU) *</label>
                    <input type="text" name="sku" id="sku" value="{{ old('sku', $product->sku ?? '') }}" required>
                    @error('sku')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="category_id">Категория *</label>
                    <select name="category_id" id="category_id" required>
                        <option value="">Выберите категорию</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $product->category_id ?? '') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <h3>Характеристики</h3>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="material">Материал *</label>
                    <input type="text" name="material" id="material" value="{{ old('material', $product->material ?? '') }}" required>
                    @error('material')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="hallmark">Проба</label>
                    <input type="text" name="hallmark" id="hallmark" value="{{ old('hallmark', $product->hallmark ?? '') }}">
                    @error('hallmark')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="weight">Вес (граммы)</label>
                    <input type="number" name="weight" id="weight" value="{{ old('weight', $product->weight ?? '') }}" step="0.01">
                    @error('weight')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="stone">Камни</label>
                    <input type="text" name="stone" id="stone" value="{{ old('stone', $product->stone ?? '') }}">
                    @error('stone')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <h3>Настройки</h3>
            
            <div class="form-row">
                <div class="form-group checkbox">
                    <label>
                        <input type="checkbox" name="is_new" value="1" {{ old('is_new', $product->is_new ?? false) ? 'checked' : '' }}>
                        Новинка
                    </label>
                </div>
                
                <div class="form-group">
                    <label for="popularity">Популярность (0-100)</label>
                    <input type="number" name="popularity" id="popularity" value="{{ old('popularity', $product->popularity ?? 0) }}" min="0" max="100">
                    @error('popularity')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
        
        <div class="form-side">
            <div class="form-group">
                <label>Изображения</label>
                <input type="file" name="images[]" multiple accept="image/*" class="file-input">
                <small>Можно выбрать несколько изображений</small>
            </div>
            
            @if(isset($product) && $product->images->isNotEmpty())
                <div class="existing-images">
                    <h4>Текущие изображения</h4>
                    <div class="image-grid">
                        @foreach($product->images as $image)
                            <div class="image-item">
                                <img src="{{ asset($image->path) }}" alt="">
                                <label>
                                    <input type="radio" name="main_image" value="{{ $image->id }}" {{ $image->is_main ? 'checked' : '' }}>
                                    Главное
                                </label>
                                <button type="button" class="remove-image" data-id="{{ $image->id }}">✕</button>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
    
    <div class="form-actions">
        <button type="submit" class="btn">Сохранить</button>
        <a href="{{ route('admin.products.index') }}" class="btn-muted">Отмена</a>
    </div>
</form>

@push('scripts')
<script>
    // Простой скрипт для генерации slug из названия
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
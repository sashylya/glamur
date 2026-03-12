@extends('layouts.app')

@section('title', 'Редактирование профиля - Гламур')

@section('content')
<div class="profile-layout">
    <h1>Редактирование профиля</h1>
    
    <div class="profile-grid">
        <div class="profile-sidebar">
            <div class="profile-menu">
                <a href="{{ route('profile.index') }}">Профиль</a>
                <a href="{{ route('profile.orders') }}">Мои заказы</a>
                <a href="{{ route('profile.edit') }}" class="active">Редактировать профиль</a>
            </div>
        </div>
        
        <div class="profile-content">
            <form method="POST" action="{{ route('profile.update') }}" class="profile-form">
                @csrf
                @method('PATCH')
                
                <h2>Основные данные</h2>
                
                <div class="form-group">
                    <label for="name">Имя *</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required>
                    @error('name')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="phone">Телефон</label>
                    <input type="tel" name="phone" id="phone" value="{{ old('phone', $user->phone) }}">
                    @error('phone')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>
                
                <h2>Адрес доставки</h2>
                
                <div class="form-group">
                    <label for="address">Адрес</label>
                    <input type="text" name="address" id="address" value="{{ old('address', $user->address) }}">
                    @error('address')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="city">Город</label>
                        <input type="text" name="city" id="city" value="{{ old('city', $user->city) }}">
                        @error('city')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                
                <button type="submit" class="btn">Сохранить изменения</button>
            </form>
        </div>
    </div>
</div>
@endsection
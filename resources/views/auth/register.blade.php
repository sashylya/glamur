@extends('layouts.app')

@section('title', 'Регистрация - Гламур')

@section('content')
<div class="auth-container">
    <h1>Регистрация</h1>
    
    <form method="POST" action="{{ route('register.store') }}" class="auth-form">
        @csrf
        
        @if(request('redirect'))
            <input type="hidden" name="redirect" value="{{ request('redirect') }}">
        @endif
        
        <div class="form-group">
            <label for="name">Имя *</label>
            <input type="text" name="name" id="name" value="{{ old('name') }}" required autofocus>
            @error('name')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="email">Email *</label>
            <input type="email" name="email" id="email" value="{{ old('email') }}" required>
            @error('email')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="phone">Телефон</label>
            <input type="text" name="phone" id="phone" value="{{ old('phone') }}">
            @error('phone')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="password">Пароль *</label>
            <input type="password" name="password" id="password" required>
            @error('password')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="password_confirmation">Подтверждение пароля *</label>
            <input type="password" name="password_confirmation" id="password_confirmation" required>
        </div>
        
        <button type="submit" class="btn">Зарегистрироваться</button>
    </form>
    
    <div class="auth-links">
        <a href="{{ route('login') }}{{ request('redirect') ? '?redirect='.urlencode(request('redirect')) : '' }}">Уже есть аккаунт? Войти</a>
    </div>
</div>
@endsection
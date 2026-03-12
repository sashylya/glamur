@extends('layouts.app')

@section('title', 'Вход - Гламур')

@section('content')
<div class="auth-container">
    <h1>Вход</h1>
    
    <form method="POST" action="{{ route('login.store') }}" class="auth-form">
        @csrf
        
        @if(request('redirect'))
            <input type="hidden" name="redirect" value="{{ request('redirect') }}">
        @endif
        
        <div class="form-group">
            <label for="email">Email *</label>
            <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus>
            @error('email')
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
        
        <div class="form-group checkbox">
            <label>
                <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                Запомнить меня
            </label>
        </div>
        
        <button type="submit" class="btn">Войти</button>
    </form>
    
    <div class="auth-links">
        <a href="{{ route('register') }}{{ request('redirect') ? '?redirect='.urlencode(request('redirect')) : '' }}">Нет аккаунта? Зарегистрироваться</a>
    </div>
</div>
@endsection
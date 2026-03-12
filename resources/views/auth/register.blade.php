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
            <input type="text" name="name" id="name" value="{{ old('name') }}" class="@error('name') error @enderror" required autofocus>
            @error('name')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="email">Email *</label>
            <input type="email" name="email" id="email" value="{{ old('email') }}" class="@error('email') error @enderror" required>
            @error('email')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="phone">Телефон *</label>
            <input type="tel" name="phone" id="phone" value="{{ old('phone') }}" class="@error('phone') error @enderror" placeholder="+7 (900) 000-00-00" required>
            @error('phone')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="password">Пароль *</label>
            <input type="password" name="password" id="password" class="@error('password') error @enderror" required>
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

@push('scripts')
<script>
    // Простая маска для телефона
    const phoneInput = document.getElementById('phone');
    if (phoneInput) {
        phoneInput.addEventListener('input', function(e) {
            let x = e.target.value.replace(/\D/g, '').match(/(\d{0,1})(\d{0,3})(\d{0,3})(\d{0,2})(\d{0,2})/);
            if (!x[2] && x[1] !== '') {
                e.target.value = x[1] === '7' || x[1] === '8' ? '+7 ' : '+7 ' + x[1];
            } else {
                e.target.value = !x[3] ? '+7 (' + x[2] : '+7 (' + x[2] + ') ' + x[3] + (x[4] ? '-' + x[4] : '') + (x[5] ? '-' + x[5] : '');
            }
        });
    }
</script>
@endpush
@endsection
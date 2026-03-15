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
            {{-- Сообщение об успехе --}}
            @if(session('success'))
                <div style="background: #d4edda; color: #155724; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Сообщение об ошибке --}}
            @if($errors->any())
                <div style="background: #f8d7da; color: #721c24; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                    <ul style="margin: 0; padding-left: 20px;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('profile.update') }}" class="profile-form">
                @csrf
                @method('PATCH')
                
                <h2>Основные данные</h2>
                
                <div class="form-group">
                    <label for="name">Имя *</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required>
                    @error('name')
                        <div style="color: #dc3545; font-size: 13px; margin-top: 5px;">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="phone">Телефон</label>
                    <input type="tel" name="phone" id="phone" value="{{ old('phone', $user->phone) }}" placeholder="+7 (999) 000-00-00">
                    @error('phone')
                        <div style="color: #dc3545; font-size: 13px; margin-top: 5px;">{{ $message }}</div>
                    @enderror
                </div>
                
                <h2>Смена пароля (необязательно)</h2>
                
                <div class="form-group">
                    <label for="current_password">Текущий пароль</label>
                    <input type="password" name="current_password" id="current_password">
                </div>
                
                <div class="form-group">
                    <label for="new_password">Новый пароль</label>
                    <input type="password" name="new_password" id="new_password">
                </div>
                
                <div class="form-group">
                    <label for="new_password_confirmation">Подтверждение пароля</label>
                    <input type="password" name="new_password_confirmation" id="new_password_confirmation">
                </div>
                
                <button type="submit" class="btn">Сохранить изменения</button>
            </form>
        </div>
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
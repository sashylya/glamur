@extends('layouts.app')

@section('title', 'Оформление заказа - Гламур')

@section('content')
<h1>Оформление заказа</h1>

<div class="checkout-layout">
    <div class="checkout-form">
        <form method="POST" action="{{ route('checkout.store') }}">
            @csrf
            
            <h2>Контактные данные</h2>
            
            <div class="form-group">
                <label for="first_name">Имя *</label>
                <input type="text" name="first_name" id="first_name" value="{{ old('first_name', $user->name ?? '') }}" class="@error('first_name') error @enderror" required>
                @error('first_name')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="form-group">
                <label for="last_name">Фамилия *</label>
                <input type="text" name="last_name" id="last_name" value="{{ old('last_name') }}" class="@error('last_name') error @enderror" required>
                @error('last_name')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="form-group">
                <label for="email">Email *</label>
                <input type="email" name="email" id="email" value="{{ old('email', $user->email ?? '') }}" class="@error('email') error @enderror" required>
                @error('email')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="form-group">
                <label for="phone">Телефон *</label>
                <input type="tel" name="phone" id="phone" value="{{ old('phone', $user->phone ?? '') }}" class="@error('phone') error @enderror" placeholder="+7 (900) 000-00-00" required>
                @error('phone')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            
            <h2>Самовывоз</h2>
            <div class="pickup-info" style="background: #1d1e27; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                <p style="margin: 0 0 10px 0;"><strong>Адрес самовывоза:</strong> г. Москва, ул. Примерная, д. 123</p>
                <p style="margin: 0; color: #666; font-size: 14px;">Режим работы: Пн-Пт с 10:00 до 20:00, Сб-Вс с 11:00 до 18:00</p>
                <input type="hidden" name="address" value="г. Москва, ул. Тверская, д. 15, офис 305">
                <input type="hidden" name="city" value="Москва">
            </div>
            
            <div class="form-group">
                <label for="notes">Примечание к заказу</label>
                <textarea name="notes" id="notes" rows="3" class="@error('notes') error @enderror" placeholder="Например: удобное время для самовывоза">{{ old('notes') }}</textarea>
                @error('notes')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            
            @guest
                <div class="form-group checkbox">
                    <label>
                        <input type="checkbox" name="create_account" value="1" {{ old('create_account') ? 'checked' : '' }}>
                        Зарегистрироваться (получить доступ к истории заказов)
                    </label>
                </div>
                
                <div id="password-fields" style="display: {{ old('create_account') ? 'block' : 'none' }};">
                    <div class="form-group">
                        <label for="password">Пароль</label>
                        <input type="password" name="password" id="password" class="@error('password') error @enderror">
                        @error('password')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="password_confirmation">Подтверждение пароля</label>
                        <input type="password" name="password_confirmation" id="password_confirmation">
                    </div>
                </div>
            @endguest
            
            <button type="submit" class="btn">Подтвердить заказ</button>
        </form>
    </div>
    
    <div class="order-summary">
        <h2>Ваш заказ</h2>
        
        @foreach($cart->items as $item)
            <div class="order-item">
                <span>{{ $item->product->name }} x {{ $item->quantity }}</span>
                <span>{{ number_format($item->product->price * $item->quantity, 0, '.', ' ') }} руб.</span>
            </div>
        @endforeach
        
        <div class="order-total">
            <strong>Итого:</strong>
            <strong id="checkout-total">{{ number_format($cart->total, 0, '.', ' ') }} руб.</strong>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Показ/скрытие пароля
    document.querySelector('[name="create_account"]')?.addEventListener('change', function() {
        document.getElementById('password-fields').style.display = this.checked ? 'block' : 'none';
    });

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
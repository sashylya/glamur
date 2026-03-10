<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Гламур - Магазин украшений')</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <div class="topbar">
        <div class="container topbar-inner">
            <a href="{{ route('home') }}" class="logo">ГЛАМУР</a>
            
            <nav class="main-nav">
                <a href="{{ route('catalog.index') }}">Каталог</a>
                <a href="{{ route('about') }}">О нас</a>
                <a href="{{ route('delivery') }}">Доставка</a>
                <a href="{{ route('contacts') }}">Контакты</a>
                <a href="{{ route('wishlist.index') }}" class="wishlist-link">Избранное</a>
            </nav>
            
            <div class="top-actions">
                <a href="{{ route('cart.index') }}" class="cart-link">
                    Корзина 
                </a>
                
                @auth
                    <a href="{{ route('profile.index') }}">Личный кабинет</a>
                    @if(Auth::user()->is_admin)
                        <a href="{{ route('admin.dashboard') }}">Админка</a>
                    @endif
                    <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                        @csrf
                        <button type="submit" class="link-btn">Выйти</button>
                    </form>
                @else
                    <a href="{{ route('login') }}">Войти</a>
                    <a href="{{ route('register') }}" class="btn-muted">Регистрация</a>
                @endauth
            </div>
        </div>
    </div>

    <main class="page">
        <div class="container">
            @if(session('success'))
                <div class="notice success">{{ session('success') }}</div>
            @endif
            
            @if(session('error'))
                <div class="notice error">{{ session('error') }}</div>
            @endif
            
            @yield('content')
        </div>
    </main>

    <footer class="footer">
        <div class="container">
            <div class="footer-grid">
                <div>
                    <h4>Гламур</h4>
                    <p>Магазин украшений<br>в темной эстетике</p>
                </div>
                <div>
                    <h4>Информация</h4>
                    <ul>
                        <li><a href="{{ route('about') }}">О нас</a></li>
                        <li><a href="{{ route('delivery') }}">Доставка</a></li>
                        <li><a href="{{ route('contacts') }}">Контакты</a></li>
                    </ul>
                </div>
                <div>
                    <h4>Контакты</h4>
                    <p>Email: info@glamur.ru<br>
                    Телефон: +7 (999) 123-45-67</p>
                </div>
            </div>
        </div>
    </footer>
    
    <script src="{{ asset('js/app.js') }}" defer></script>
    @stack('scripts')
</body>
</html>
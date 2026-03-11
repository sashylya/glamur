<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Админ-панель - Гламур')</title>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <div class="admin-layout">
        <aside class="admin-sidebar">
            <div class="sidebar-header">
                <a href="{{ route('admin.dashboard') }}" class="logo">GLAMUR</a>
                <span class="admin-badge">Админ</span>
            </div>
            
            <nav class="admin-nav">
                <ul>
                    <li class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <a href="{{ route('admin.dashboard') }}">📊 Дашборд</a>
                    </li>
                    <li class="{{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                        <a href="{{ route('admin.products.index') }}">📦 Товары</a>
                    </li>
                    <li class="{{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                        <a href="{{ route('admin.categories.index') }}">📁 Категории</a>
                    </li>
                    <li class="{{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                        <a href="{{ route('admin.orders.index') }}">📋 Заказы</a>
                    </li>
                    <li class="{{ request()->routeIs('admin.reviews.*') ? 'active' : '' }}">
                        <a href="{{ route('admin.reviews.index') }}">⭐ Отзывы</a>
                    </li>
                </ul>
            </nav>
            
            <div class="sidebar-footer">
                <a href="{{ route('home') }}" class="back-to-site">← На сайт</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="logout-btn">Выйти</button>
                </form>
            </div>
        </aside>
        
        <main class="admin-main">
            <div class="admin-header">
                <h1>@yield('header')</h1>
                <div class="admin-user">
                    {{ Auth::user()->name }}
                </div>
            </div>
            
            <div class="admin-content">
                @if(session('success'))
                    <div class="notice success">{{ session('success') }}</div>
                @endif
                
                @if(session('error'))
                    <div class="notice error">{{ session('error') }}</div>
                @endif
                
                @yield('content')
            </div>
        </main>
    </div>
    
    @stack('scripts')
</body>
</html>
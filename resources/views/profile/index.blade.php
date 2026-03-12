@extends('layouts.app')

@section('title', 'Личный кабинет - Гламур')

@section('content')
<div class="profile-layout">
    <h1>Личный кабинет</h1>
    
    <div class="profile-grid">
        <div class="profile-sidebar">
            <div class="profile-menu">
                <a href="{{ route('profile.index') }}" class="{{ request()->routeIs('profile.index') ? 'active' : '' }}">Профиль</a>
                <a href="{{ route('profile.orders') }}" class="{{ request()->routeIs('profile.orders') ? 'active' : '' }}">Мои заказы</a>
                <a href="{{ route('profile.edit') }}" class="{{ request()->routeIs('profile.edit') ? 'active' : '' }}">Редактировать профиль</a>
            </div>
        </div>

        <div class="profile-content">
            <div class="profile-info">
                <h2>Мои данные</h2>
                
                <table class="profile-table">
                    <tr>
                        <th>Имя:</th>
                        <td>{{ $user->name }}</td>
                    </tr>
                    <tr>
                        <th>Email:</th>
                        <td>{{ $user->email }}</td>
                    </tr>
                    @if($user->phone)
                    <tr>
                        <th>Телефон:</th>
                        <td>{{ $user->phone }}</td>
                    </tr>
                    @endif
                    @if($user->address)
                    <tr>
                        <th>Адрес:</th>
                        <td>{{ $user->address }}</td>
                    </tr>
                    @endif
                    @if($user->city)
                    <tr>
                        <th>Город:</th>
                        <td>{{ $user->city }}</td>
                    </tr>
                    @endif
                </table>
            </div>
            
            @if($user->orders->isNotEmpty())
                <div class="recent-orders">
                    <h2>Последние заказы</h2>
                    
                    <div class="orders-list">
                        @foreach($user->orders->take(3) as $order)
                            @php
                                $statuses = [
                                    'pending' => 'В обработке',
                                    'processing' => 'В обработке',
                                    'confirmed' => 'Подтвержден',
                                    'shipped' => 'Отправлен',
                                    'delivered' => 'Доставлен',
                                    'completed' => 'Завершен',
                                    'cancelled' => 'Отменен',
                                    'refunded' => 'Возврат',
                                    'failed' => 'Ошибка',
                                    'new' => 'Новый',
                                    'paid' => 'Оплачен',
                                    'awaiting_payment' => 'Ожидает оплаты'
                                ];
                                $statusText = $statuses[$order->status] ?? $order->status;
                            @endphp
                            <div class="order-item">
                                <div class="order-header">
                                    <span class="order-number">Заказ #{{ $order->order_number ?? $order->id }}</span>
                                    <span class="order-date">{{ $order->created_at->format('d.m.Y') }}</span>
                                    <span class="order-status">{{ $statusText }}</span>
                                    <span class="order-total">{{ number_format($order->total_amount, 0, '.', ' ') }} руб.</span>
                                </div>
                                <a href="{{ route('profile.order', $order) }}" class="btn-muted">Подробнее</a>
                            </div>
                        @endforeach
                    </div>
                    
                    @if($user->orders->count() > 3)
                        <a href="{{ route('profile.orders') }}" class="btn-muted">Все заказы</a>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
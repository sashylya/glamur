@extends('layouts.app')

@section('title', 'Мои заказы - Гламур')

@section('content')
<div class="profile-layout">
    <h1>Мои заказы</h1>
    
    <div class="profile-grid">
        <div class="profile-sidebar">
            <div class="profile-menu">
                <a href="{{ route('profile.index') }}">Профиль</a>
                <a href="{{ route('profile.orders') }}" class="active">Мои заказы</a>
                <a href="{{ route('profile.edit') }}">Редактировать профиль</a>
            </div>
        </div>
        
        <div class="profile-content">
            @if($orders->isNotEmpty())
                <div class="orders-table">
                    <table>
                        <thead>
                            <tr>
                                <th>Номер заказа</th>
                                <th>Дата</th>
                                <th>Сумма</th>
                                <th>Статус</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                                <tr>
                                    <td>#{{ $order->order_number }}</td>
                                    <td>{{ $order->created_at->format('d.m.Y H:i') }}</td>
                                    <td>{{ number_format($order->total_amount, 0, '.', ' ') }} руб.</td>
                                    <td>
                                        @switch($order->status)
                                            @case('new')
                                                <span class="status-new">Новый</span>
                                                @break
                                            @case('processing')
                                                <span class="status-processing">В обработке</span>
                                                @break
                                            @case('completed')
                                                <span class="status-completed">Выполнен</span>
                                                @break
                                            @case('cancelled')
                                                <span class="status-cancelled">Отменен</span>
                                                @break
                                            @default
                                                <span>{{ $order->status }}</span>
                                        @endswitch
                                    </td>
                                    <td>
                                        <a href="{{ route('profile.order', $order) }}" class="btn-muted">Подробнее</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="pagination">
                    {{ $orders->links() }}
                </div>
            @else
                <p>У вас пока нет заказов</p>
                <a href="{{ route('catalog.index') }}" class="btn">Перейти в каталог</a>
            @endif
        </div>
    </div>
</div>
@endsection
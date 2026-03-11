@extends('layouts.admin')

@section('title', 'Дашборд - Админ-панель')
@section('header', 'Дашборд')

@section('content')
<div class="dashboard">
    <!-- Статистика -->
    <div class="stats-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px;">
        <div style="background:  #7c2b2b; padding: 25px; border-radius: 12px; color: white; box-shadow: 0 4px 12px rgba(102, 126, 234, 0.2);">
            <div style="font-size: 36px; font-weight: bold; margin-bottom: 5px;">{{ $totalProducts ?? 0 }}</div>
            <div style="font-size: 14px; opacity: 0.9;">Товаров</div>
        </div>
        
        <div style="background:  #7c2b2b; padding: 25px; border-radius: 12px; color: white; box-shadow: 0 4px 12px rgba(240, 147, 251, 0.2);">
            <div style="font-size: 36px; font-weight: bold; margin-bottom: 5px;">{{ $totalOrders ?? 0 }}</div>
            <div style="font-size: 14px; opacity: 0.9;">Заказов</div>
        </div>
        
        <div style="background:  #7c2b2b; padding: 25px; border-radius: 12px; color: white; box-shadow: 0 4px 12px rgba(79, 172, 254, 0.2);">
            <div style="font-size: 36px; font-weight: bold; margin-bottom: 5px;">{{ $totalUsers ?? 0 }}</div>
            <div style="font-size: 14px; opacity: 0.9;">Пользователей</div>
        </div>
        
        <div style="background:  #7c2b2b; padding: 25px; border-radius: 12px; color: white; box-shadow: 0 4px 12px rgba(67, 233, 123, 0.2);">
            <div style="font-size: 36px; font-weight: bold; margin-bottom: 5px;">{{ number_format($totalRevenue ?? 0, 0, '.', ' ') }} ₽</div>
            <div style="font-size: 14px; opacity: 0.9;">Выручка</div>
        </div>
        
        
        <div style="background: linear-gradient(135deg, #fa709a 0%, #7c2b2b 100%); padding: 25px; border-radius: 12px; color: white; box-shadow: 0 4px 12px rgba(250, 112, 154, 0.2);">
            <div style="font-size: 36px; font-weight: bold; margin-bottom: 5px;">{{ $pendingReviews ?? 0 }}</div>
            <div style="font-size: 14px; opacity: 0.9;">На модерации</div>
            @if(($pendingReviews ?? 0) > 0)
                <a href="{{ route('admin.reviews.index') }}" style="color: white; text-decoration: underline; font-size: 13px; margin-top: 10px; display: inline-block;">Перейти →</a>
            @endif
        </div>
    </div>
    </div>
    
   
    <!-- Последние заказы -->
    <div style="background: #1d1e27; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); padding: 20px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h2 style="font-size: 18px; color: #ffffff;">Последние заказы</h2>
            <a href="{{ route('admin.orders.index') }}" style="color: #667eea; text-decoration: none; font-size: 14px;">Все заказы →</a>
        </div>
        
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="border-bottom: 2px solid #f0f0f0;">
                    <th style="padding: 12px; text-align: left; color: #666; font-weight: 500;">№ заказа</th>
                    <th style="padding: 12px; text-align: left; color: #666; font-weight: 500;">Клиент</th>
                    <th style="padding: 12px; text-align: left; color: #666; font-weight: 500;">Сумма</th>
                    <th style="padding: 12px; text-align: left; color: #666; font-weight: 500;">Статус</th>
                    <th style="padding: 12px; text-align: left; color: #666; font-weight: 500;">Дата</th>
                    <th style="padding: 12px; text-align: left; color: #666; font-weight: 500;"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentOrders ?? [] as $order)
                    <tr style="border-bottom: 1px solid #f0f0f0;">
                        <td style="padding: 12px; font-weight: 500;">#{{ $order->order_number }}</td>
                        <td style="padding: 12px;">
                            <div style="font-weight: 500;">{{ $order->first_name }} {{ $order->last_name }}</div>
                            <div style="font-size: 12px; color: #999;">{{ $order->email }}</div>
                        </td>
                        <td style="padding: 12px; font-weight: 500;">{{ number_format($order->total_amount, 0, '.', ' ') }} ₽</td>
                        <td style="padding: 12px;">
                            <span style="padding: 4px 12px; border-radius: 20px; font-size: 12px; 
                                @if($order->status == 'completed') background: #d4edda; color: #155724;
                                @elseif($order->status == 'processing') background: #fff3cd; color: #856404;
                                @elseif($order->status == 'new') background: #cce5ff; color: #004085;
                                @else background: #f8d7da; color: #721c24; @endif">
                                @switch($order->status)
                                    @case('completed') Выполнен @break
                                    @case('processing') В обработке @break
                                    @case('new') Новый @break
                                    @default Отменен
                                @endswitch
                            </span>
                        </td>
                        <td style="padding: 12px; color: #666;">{{ $order->created_at->format('d.m.Y') }}</td>
                        <td style="padding: 12px;">
                            <a href="{{ route('admin.orders.show', $order) }}" style="color: #667eea; text-decoration: none; font-size: 13px;">Просмотр</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="padding: 40px; text-align: center; color: #999;">Нет заказов</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
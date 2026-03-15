@extends('layouts.app')

@section('title', 'Заказ #' . $order->order_number . ' - Гламур')

@section('content')
<div style="max-width: 1000px; margin: 0 auto; padding: 20px;">
    <!-- Шапка -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
        <h1 style="margin: 0; font-size: 24px;">Заказ #{{ $order->order_number }}</h1>
        <a href="{{ route('profile.orders') }}" style="color: #666; text-decoration: none; font-size: 14px;">← Назад к заказам</a>
    </div>
    
    <!-- Статус -->
    <div style="background: #1d1e27; padding: 20px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #eee;">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <span style="font-weight: 500; color: #ffffff;">Статус заказа:</span>
            <span style="font-weight: 600; 
                @if($order->status == 'new') color: #004085; background: #cce5ff; padding: 4px 12px; border-radius: 20px;
                @elseif($order->status == 'processing') color: #856404; background: #fff3cd; padding: 4px 12px; border-radius: 20px;
                @elseif($order->status == 'completed') color: #155724; background: #d4edda; padding: 4px 12px; border-radius: 20px;
                @elseif($order->status == 'cancelled') color: #721c24; background: #f8d7da; padding: 4px 12px; border-radius: 20px;
                @endif">
                @switch($order->status)
                    @case('new') Новый @break
                    @case('processing') В обработке @break
                    @case('completed') Выполнен @break
                    @case('cancelled') Отменен @break
                @endswitch
            </span>
        </div>
        <div style="margin-top: 10px; color: #ffffff; font-size: 14px;">
            Дата заказа: {{ $order->created_at->format('d.m.Y H:i') }}
        </div>
    </div>
    
    <!-- Информация в две колонки -->
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 30px;">
        <!-- Получатель -->
        <div style="background: #1d1e27; padding: 20px; border-radius: 8px; border: 1px solid #eee;">
            <h3 style="margin: 0 0 15px 0; font-size: 16px; color: #ffffff;">Получатель</h3>
            <div style="margin-bottom: 8px;"><strong>{{ $order->first_name }} {{ $order->last_name }}</strong></div>
            <div style="margin-bottom: 5px; color: #ffffff; font-size: 14px;">Email: {{ $order->email }}</div>
            <div style="color: #ffffff; font-size: 14px;">Телефон: {{ $order->phone }}</div>
        </div>
        
        <!-- Доставка -->
        <div style="background: #1d1e27; padding: 20px; border-radius: 8px; border: 1px solid #eee;">
            <h3 style="margin: 0 0 15px 0; font-size: 16px; color: #ffffff;">Доставка</h3>
            <div style="color: #ffffff; font-size: 14px; line-height: 1.6;">{{ $order->address }}, {{ $order->city }}</div>
            @if($order->notes)
                <div style="margin-top: 10px; padding-top: 10px; border-top: 1px dashed #ddd;">
                    <div style="font-size: 13px; color: #ffffff;">Примечание:</div>
                    <div style="color: #ffffff; font-size: 14px;">{{ $order->notes }}</div>
                </div>
            @endif
        </div>
    </div>
    
    <!-- Состав заказа -->
    <div style="background: #1d1e27; padding: 20px; border-radius: 8px; border: 1px solid #eee;">
        <h3 style="margin: 0 0 20px 0; font-size: 16px; color: #ffffff;">Состав заказа</h3>
        
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="border-bottom: 2px solid #ddd;">
                    <th style="padding: 10px; text-align: left; font-size: 14px; font-weight: 500; color: #666;">Товар</th>
                    <th style="padding: 10px; text-align: center; font-size: 14px; font-weight: 500; color: #666;">Кол-во</th>
                    <th style="padding: 10px; text-align: right; font-size: 14px; font-weight: 500; color: #666;">Цена</th>
                    <th style="padding: 10px; text-align: right; font-size: 14px; font-weight: 500; color: #666;">Сумма</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                    <tr style="border-bottom: 1px solid #eee;">
                        <td style="padding: 15px 10px;">{{ $item->product_name }}</td>
                        <td style="padding: 15px 10px; text-align: center;">{{ $item->quantity }}</td>
                        <td style="padding: 15px 10px; text-align: right;">{{ number_format($item->price, 0, '.', ' ') }} ₽</td>
                        <td style="padding: 15px 10px; text-align: right; font-weight: 500;">{{ number_format($item->price * $item->quantity, 0, '.', ' ') }} ₽</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="3" style="padding: 15px 10px; text-align: right; font-size: 16px;">Итого:</th>
                    <th style="padding: 15px 10px; text-align: right; font-size: 18px; color: #c44d70;">{{ number_format($order->total_amount, 0, '.', ' ') }} ₽</th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
@endsection
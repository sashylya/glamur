<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Создаем новую таблицу без поля order_number
        Schema::create('orders_temp', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->string('status')->default('new');
            $table->decimal('total_amount', 10, 2);
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email');
            $table->string('phone');
            $table->text('address');
            $table->string('city');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // Получаем все заказы из старой таблицы
        $orders = DB::table('orders')->get();

        // Копируем данные по одному (более надежный способ)
        foreach ($orders as $order) {
            DB::table('orders_temp')->insert([
                'id' => $order->id,
                'user_id' => $order->user_id,
                'status' => $order->status,
                'total_amount' => $order->total_amount,
                'first_name' => $order->first_name,
                'last_name' => $order->last_name,
                'email' => $order->email,
                'phone' => $order->phone,
                'address' => $order->address,
                'city' => $order->city,
                'notes' => $order->notes,
                'created_at' => $order->created_at,
                'updated_at' => $order->updated_at
            ]);
        }

        // Удаляем старую таблицу
        Schema::dropIfExists('orders');

        // Переименовываем временную таблицу
        Schema::rename('orders_temp', 'orders');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Восстанавливаем таблицу с полем order_number
        Schema::create('orders_old', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->string('order_number')->unique();
            $table->string('status')->default('new');
            $table->decimal('total_amount', 10, 2);
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email');
            $table->string('phone');
            $table->text('address');
            $table->string('city');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // Восстанавливаем данные
        $orders = DB::table('orders')->get();
        foreach ($orders as $order) {
            DB::table('orders_old')->insert([
                'id' => $order->id,
                'user_id' => $order->user_id,
                'order_number' => 'ORD-' . uniqid(),
                'status' => $order->status,
                'total_amount' => $order->total_amount,
                'first_name' => $order->first_name,
                'last_name' => $order->last_name,
                'email' => $order->email,
                'phone' => $order->phone,
                'address' => $order->address,
                'city' => $order->city,
                'notes' => $order->notes,
                'created_at' => $order->created_at,
                'updated_at' => $order->updated_at
            ]);
        }

        Schema::dropIfExists('orders');
        Schema::rename('orders_old', 'orders');
    }
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('sku')->unique();
            $table->text('description');
            $table->string('material', 50); // материал (золото, серебро)
            $table->string('hallmark', 20)->nullable(); // проба (585, 925)
            $table->decimal('weight', 8, 2)->nullable(); // вес в граммах
            $table->string('stone')->nullable(); // камни
            $table->decimal('price', 10, 2);
            $table->unsignedInteger('stock')->default(0); // остаток на складе
            $table->unsignedInteger('popularity')->default(0); // для сортировки популярных
            $table->boolean('is_new')->default(false); // новинка
            $table->boolean('is_active')->default(true); // активен ли товар
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
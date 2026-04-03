<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stocks', function (Blueprint $table) {
            $table->id();
            $table->date('date'); // дата записи остатков
            $table->date('last_change_date')->nullable(); // дата последнего изменения
            $table->string('supplier_article', 255)->nullable(); // артикул поставщика
            $table->string('tech_size', 255)->nullable(); // технический размер
            $table->bigInteger('barcode'); // штрих‑код
            $table->integer('quantity'); // текущее количество
            $table->boolean('is_supply')->nullable(); // флаг поставки
            $table->boolean('is_realization')->nullable(); // флаг реализации
            $table->integer('quantity_full')->nullable(); // полное количество
            $table->string('warehouse_name', 255); // название склада
            $table->integer('in_way_to_client')->nullable(); // в пути к клиенту
            $table->integer('in_way_from_client')->nullable(); // в пути от клиента
            $table->bigInteger('nm_id'); // ID номенклатуры
            $table->string('subject', 255)->nullable(); // предмет/тип товара
            $table->string('category', 255)->nullable(); // категория товара
            $table->string('brand', 255)->nullable(); // бренд
            $table->string('sc_code', 255)->nullable(); // код склада/системы
            $table->decimal('price', 12, 2)->nullable(); // цена
            $table->decimal('discount', 5, 2)->nullable(); // скидка (в процентах или сумме)

            $table->timestamps(); // created_at и updated_at

            // Индексы для ускорения поиска
            $table->index('date');
            $table->index('warehouse_name');
            $table->index('nm_id');
            $table->index('barcode');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stocks');
    }
};

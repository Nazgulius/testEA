<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('incomes', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('income_id')->unique(); // уникальный ID поступления
            $table->string('number', 255)->nullable(); // номер поступления (может быть пустым)
            $table->date('date'); // дата поступления
            $table->date('last_change_date')->nullable(); // дата последнего изменения
            $table->string('supplier_article', 255); // артикул поставщика
            $table->string('tech_size', 255); // технический размер
            $table->bigInteger('barcode'); // штрих‑код
            $table->integer('quantity'); // количество единиц товара
            $table->decimal('total_price', 12, 2); // общая цена
            $table->date('date_close')->nullable(); // дата закрытия поступления
            $table->string('warehouse_name', 255); // название склада
            $table->bigInteger('nm_id'); // ID номенклатуры

            $table->timestamps(); // created_at и updated_at

            // Индексы для ускорения поиска
            $table->index('date');
            $table->index('warehouse_name');
            $table->index('nm_id');
            $table->index('income_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('incomes');
    }
};

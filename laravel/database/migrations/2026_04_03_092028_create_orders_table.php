<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('g_number')->unique(); // уникальный идентификатор заказа
            $table->dateTime('date'); // дата и время заказа
            $table->date('last_change_date')->nullable(); // дата последнего изменения
            $table->string('supplier_article', 255); // артикул поставщика
            $table->string('tech_size', 255); // технический размер
            $table->bigInteger('barcode'); // штрих‑код
            $table->decimal('total_price', 12, 2); // общая цена с учётом скидки
            $table->integer('discount_percent')->nullable(); // процент скидки
            $table->string('warehouse_name', 255); // название склада
            $table->string('oblast', 255)->nullable(); // область/регион
            $table->bigInteger('income_id')->nullable(); // ID поступления
            $table->string('odid'); // ID позиции заказа
            $table->bigInteger('nm_id'); // ID номенклатуры
            $table->string('subject', 255); // предмет/тип товара
            $table->string('category', 255); // категория товара
            $table->string('brand', 255); // бренд
            $table->boolean('is_cancel')->default(false); // флаг отмены заказа
            $table->dateTime('cancel_dt')->nullable(); // дата отмены заказа
            $table->timestamps();

          
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};

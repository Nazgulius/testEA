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
            $table->string('g_number')->unique(); // уникальный идентификатор
            $table->date('date');
            $table->date('last_change_date')->nullable();
            $table->string('supplier_article');
            $table->string('tech_size');
            $table->bigInteger('barcode');
            $table->decimal('total_price', 10, 2);
            $table->integer('discount_percent')->nullable();
            $table->boolean('is_supply')->default(false);
            $table->boolean('is_realization')->default(false);
            $table->text('promo_code_discount')->nullable();
            $table->string('warehouse_name', 255);
            $table->string('country_name')->nullable()->change();
            $table->string('oblast_okrug_name', 255);
            $table->string('region_name', 255);
            $table->bigInteger('income_id')->nullable();
            $table->string('sale_id')->unique();
            $table->bigInteger('odid')->nullable();
            $table->string('spp', 50)->nullable();
            $table->decimal('for_pay', 10, 2)->nullable();
            $table->decimal('finished_price', 10, 2)->nullable();
            $table->decimal('price_with_disc', 10, 2)->nullable();
            $table->bigInteger('nm_id');
            $table->string('subject', 255);
            $table->string('category', 255);
            $table->string('brand', 255);
            $table->boolean('is_storno')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};

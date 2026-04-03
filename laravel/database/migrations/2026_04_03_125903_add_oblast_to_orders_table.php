<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Добавляем поле oblast, если его ещё нет
            if (!Schema::hasColumn('orders', 'oblast')) {
                $table->string('oblast', 255)->nullable()->after('warehouse_name');
            }
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('oblast');
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('schedules', function (Blueprint $table) {
            Schema::table('schedules', function (Blueprint $table) {
            //schedulesテーブルにカラムを追加
            $table->boolean('is_notification')->default(false); 
        });
        });
    }

    
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('schedules', function (Blueprint $table) {
            // ロールバック時にカラムを削除するための記述
            $table->dropColumn('is_notification');
        });
    }
};

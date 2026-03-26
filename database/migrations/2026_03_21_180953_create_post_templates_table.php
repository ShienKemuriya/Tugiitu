<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('post_templates', function (Blueprint $table) {
            $table->id();

            // ユーザー紐づけ（必須）
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            // テンプレ内容
            $table->string('title');
            $table->text('body')->nullable();
            $table->string('genre')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('post_templates');
    }
};
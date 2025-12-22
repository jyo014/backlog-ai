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
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            // 誰の予定か
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // 予定の中身
            $table->string('title');        // タイトル
            $table->dateTime('start_date'); // 開始日時
            $table->dateTime('end_date');   // 終了日時
            $table->string('color')->default('#3b82f6'); // 色
            $table->text('description')->nullable();     // 詳細メモ（NULL許可）
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};

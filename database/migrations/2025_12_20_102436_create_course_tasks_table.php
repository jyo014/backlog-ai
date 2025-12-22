<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('course_tasks', function (Blueprint $table) {
        $table->id();
        // どの授業の課題か
        $table->foreignId('course_id')->constrained()->onDelete('cascade');
        // 誰が追加したか
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        
        $table->string('title');       // タイトル（例：中間レポート）
        $table->dateTime('due_date');  // 期限・日程
        $table->text('description')->nullable(); // 詳細メモ
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_tasks');
    }
};

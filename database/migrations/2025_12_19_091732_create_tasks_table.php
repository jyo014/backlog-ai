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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('title');//課題の名前
            $table->date('deadline');//期限
            $table->string('status')->default('未着手');//ステータス

            // 優先度
            $table->string('priority')->default('中');//優先度
            $table->integer('progress')->default(0);//進捗度合い（パーセンテージ）
            $table->string('backlog_key')->nullable(); // 担当者
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};

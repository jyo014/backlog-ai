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
        // 1. チーム（大学）テーブルを作成
        Schema::create('teams', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // 大学名・チーム名
            $table->timestamps();
        });

        // 2. ユーザーに「所属チームID」を追加
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('team_id')->nullable()->constrained('teams');
        });

        // 3. タスクに「作業時間」と「チームID」を追加
        Schema::table('tasks', function (Blueprint $table) {
            $table->integer('duration')->default(0); // 作業時間（分）
            $table->foreignId('team_id')->nullable()->constrained('teams'); // チーム共有課題ならIDが入る
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};

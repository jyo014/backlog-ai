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
    Schema::create('courses', function (Blueprint $table) {
        $table->id();
        $table->string('university_name'); // 大学名（将来的にテーブル分けるかもだけど、一旦文字で）
        $table->string('course_name');     // 授業名（例：基礎ロボット工学）
        $table->string('day_of_week');     // 曜日（例：月曜日）
        $table->integer('period');         // 何限目か（例：1）
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};

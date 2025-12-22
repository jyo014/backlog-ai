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
    Schema::create('course_user', function (Blueprint $table) {
        $table->id();
        // 誰が？
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        // どの授業を？
        $table->foreignId('course_id')->constrained()->onDelete('cascade');
        
        // 同じ授業を2回登録できないようにする設定
        $table->unique(['user_id', 'course_id']);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_user');
    }
};

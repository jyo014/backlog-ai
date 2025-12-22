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
    Schema::table('courses', function (Blueprint $table) {
        if (!Schema::hasColumn('courses', 'teacher_name')) {
            $table->string('teacher_name')->nullable()->after('course_name');
        }
    });
}

public function down()
{
    Schema::table('courses', function (Blueprint $table) {
        if (Schema::hasColumn('courses', 'teacher_name')) {
            $table->dropColumn('teacher_name');
        }
    });
}
};

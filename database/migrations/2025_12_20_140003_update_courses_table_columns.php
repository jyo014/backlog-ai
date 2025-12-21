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
        // university_name がまだない場合は追加
        if (!Schema::hasColumn('courses', 'university_name')) {
            $table->string('university_name')->nullable()->after('id');
        }
        
        // name カラムがある場合、course_name に変更
        if (Schema::hasColumn('courses', 'name')) {
            $table->renameColumn('name', 'course_name');
        }
    });
}

public function down()
{
    Schema::table('courses', function (Blueprint $table) {
        if (Schema::hasColumn('courses', 'course_name')) {
            $table->renameColumn('course_name', 'name');
        }
        $table->dropColumn('university_name');
    });
}
};

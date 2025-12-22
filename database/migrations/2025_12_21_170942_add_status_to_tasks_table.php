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
        Schema::table('tasks', function (Blueprint $table) {
            // 'status' カラムがまだない場合のみ追加する
            if (!Schema::hasColumn('tasks', 'status')) {
                $table->string('status')->default('todo')->after('title');
            }

            // 'backlog_key' カラムがまだない場合のみ追加する
            if (!Schema::hasColumn('tasks', 'backlog_key')) {
                $table->string('backlog_key')->nullable()->after('status'); // statusの後ろに追加
            }
        });
    }

    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropColumn(['status', 'backlog_key']);
        });
    }
};

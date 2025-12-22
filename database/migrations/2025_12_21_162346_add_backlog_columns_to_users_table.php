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
        Schema::table('users', function (Blueprint $table) {
            // BacklogのスペースID（例: my-company.backlog.jp）
            $table->string('backlog_domain')->nullable()->after('email');
            // BacklogのAPIキー
            $table->string('backlog_api_key')->nullable()->after('backlog_domain');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['backlog_domain', 'backlog_api_key']);
        });
    }
};

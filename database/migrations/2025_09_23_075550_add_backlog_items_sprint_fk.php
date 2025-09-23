<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('backlog_items', function (Blueprint $table) {
            $table->foreign('sprint_id')->references('id')->on('sprints')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('backlog_items', function (Blueprint $table) {
            $table->dropForeign(['sprint_id']);
        });
    }
};



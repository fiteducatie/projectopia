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
        Schema::create('backlog_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('projects')->cascadeOnDelete();
            $table->unsignedBigInteger('sprint_id')->nullable()->index();
            $table->string('epic')->nullable();
            $table->string('title');
            $table->text('description')->nullable();
            $table->text('acceptance_criteria')->nullable();
            $table->unsignedInteger('priority')->default(3);
            $table->unsignedInteger('effort')->default(1);
            $table->json('dependencies')->nullable();
            $table->string('status')->default('todo');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('backlog_items');
    }
};

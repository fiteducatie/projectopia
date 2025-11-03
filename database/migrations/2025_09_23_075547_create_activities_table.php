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
        // if 'activities already exists, dont don anything
        if (Schema::hasTable('activities')) {
            return;
        }
        Schema::create('activities', function (Blueprint $table) {

            $table->id();
            $table->foreignId('team_id')->constrained('teams')->cascadeOnDelete();
            $table->string('name');
            $table->string('domain')->default('software');
            $table->text('context')->nullable();
            $table->text('objectives')->nullable();
            $table->text('constraints')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->text('risk_notes')->nullable();
            $table->string('difficulty')->default('normal');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activities');
    }
};


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
        Schema::table('teams', function (Blueprint $table) {
            $table->string('slug')->nullable()->after('name');
        });

        // Generate slugs for existing teams
        $teams = \App\Models\Team::whereNull('slug')->get();
        foreach ($teams as $team) {
            $team->slug = \Illuminate\Support\Str::slug($team->name);
            $team->save();
        }

        // Now make it unique and not nullable
        Schema::table('teams', function (Blueprint $table) {
            $table->string('slug')->unique()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('teams', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
};

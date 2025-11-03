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
        if (Schema::hasTable('activities') && Schema::hasColumn('activities', 'slug')) {
            return;
        }
        Schema::table('activities', function (Blueprint $table) {
            $table->string('slug')->nullable()->after('name');
        });

        // Generate slugs for existing activities
        $activities = \App\Models\Activity::whereNull('slug')->get();
        foreach ($activities as $activity) {
            $activity->slug = \Illuminate\Support\Str::slug($activity->name);
            $activity->save();
        }

        // Now make it unique and not nullable
        Schema::table('activities', function (Blueprint $table) {
            $table->string('slug')->unique()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('activities', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
};


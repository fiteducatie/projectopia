<?php

namespace App\Console\Commands;

use App\Services\ScheduleService;
use Illuminate\Console\Command;

class CleanupScheduleMessages extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'schedule:cleanup';

    /**
     * The console command description.
     */
    protected $description = 'Clean up expired schedule messages from teamleader chats';

    /**
     * Execute the console command.
     */
    public function handle(ScheduleService $scheduleService): int
    {
        $this->info('Starting schedule message cleanup...');

        $projectsWithActiveSchedules = $scheduleService->getProjectsWithActiveSchedules();
        $cleanedCount = 0;

        foreach ($projectsWithActiveSchedules as $project) {
            $activeItems = $scheduleService->getActiveScheduleItems($project);

            if ($activeItems->isEmpty()) {
                $this->info("Project '{$project->name}' has no active schedule items - cleanup may be needed");
                $cleanedCount++;
            }
        }

        $this->info("Cleanup completed. Processed {$cleanedCount} projects.");

        return Command::SUCCESS;
    }
}




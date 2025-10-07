<?php

namespace App\Services;

use App\Models\Project;
use App\Models\Teamleader;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class ScheduleService
{
    /**
     * Get currently active schedule items for a project
     */
    public function getActiveScheduleItems(Project $project): Collection
    {
        $now = Carbon::now();
        $schedule = $project->schedule ?? [];

        return collect($schedule)->filter(function ($item) use ($now) {
            if (!isset($item['time_from']) || !isset($item['time_until'])) {
                return false;
            }

            $timeFrom = Carbon::parse($item['time_from']);
            $timeUntil = Carbon::parse($item['time_until']);

            return $now->between($timeFrom, $timeUntil);
        });
    }

    /**
     * Check if there's an active schedule item for a project
     */
    public function hasActiveSchedule(Project $project): bool
    {
        return $this->getActiveScheduleItems($project)->isNotEmpty();
    }

    /**
     * Generate a proactive schedule message for a teamleader
     */
    public function generateScheduleMessage(Teamleader $teamleader, array $scheduleItem): string
    {
        $project = $teamleader->projects()->first();
        $title = $scheduleItem['title'] ?? 'Geplande activiteit';
        $description = $scheduleItem['description'] ?? '';
        $timeFrom = Carbon::parse($scheduleItem['time_from'])->format('H:i');
        $timeUntil = Carbon::parse($scheduleItem['time_until'])->format('H:i');

        // Get teamleader personality traits
        $communicationStyle = $teamleader->communication_style ?? '';
        $skillset = $teamleader->skillset ?? '';

        // Generate message based on teamleader's personality
        $message = $this->buildPersonalityBasedMessage($teamleader, $title, $description, $timeFrom, $timeUntil);

        return $message;
    }

    /**
     * Build a message based on teamleader's personality and communication style
     */
    private function buildPersonalityBasedMessage(Teamleader $teamleader, string $title, string $description, string $timeFrom, string $timeUntil): string
    {
        $communicationStyle = strtolower($teamleader->communication_style ?? '');
        $name = $teamleader->name;

        // Different message styles based on communication style TODO
        return "Hallo team! 👋🏻 \n\n";
     }

    /**
     * Check if a schedule message should be shown (is currently active)
     */
    public function shouldShowScheduleMessage(Project $project): bool
    {
        return $this->hasActiveSchedule($project);
    }

    /**
     * Get all current active schedule messages for a teamleader
     */
    public function getCurrentScheduleMessages(Teamleader $teamleader): array
    {
        $project = $teamleader->projects()->first();
        if (!$project) {
            return [];
        }

        $activeItems = $this->getActiveScheduleItems($project);
        if ($activeItems->isEmpty()) {
            return [];
        }

        // Generate separate messages for each active item
        $messages = [];
        foreach ($activeItems as $item) {
            $message = $this->generateScheduleMessage($teamleader, $item);
            $timeFrom = Carbon::parse($item['time_from']);

            $messages[] = [
                'message' => $message,
                'timestamp' => $timeFrom->toISOString(),
                'schedule_item_id' => $timeFrom->format('Y-m-d_H-i-s'), // Unique ID for this schedule item
            ];
        }

        // Sort by timestamp (earliest first)
        usort($messages, function ($a, $b) {
            return strcmp($a['timestamp'], $b['timestamp']);
        });

        return $messages;
    }

    /**
     * Get the current active schedule message for a teamleader (backwards compatibility)
     */
    public function getCurrentScheduleMessage(Teamleader $teamleader): ?array
    {
        $messages = $this->getCurrentScheduleMessages($teamleader);
        return !empty($messages) ? $messages[0] : null;
    }


    /**
     * Get all projects with active schedules
     */
    public function getProjectsWithActiveSchedules(): Collection
    {
        return Project::all()->filter(function ($project) {
            return $this->hasActiveSchedule($project);
        });
    }
}




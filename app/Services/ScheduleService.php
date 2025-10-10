<?php

namespace App\Services;

use App\Models\Activity;
use App\Models\Teamleader;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class ScheduleService
{
    /**
     * Get currently active schedule items for an activity
     */
    public function getActiveScheduleItems(Activity $activity): Collection
    {
        $now = Carbon::now();
        $schedule = $activity->schedule ?? [];

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
     * Check if there's an active schedule item for an activity
     */
    public function hasActiveSchedule(Activity $activity): bool
    {
        return $this->getActiveScheduleItems($activity)->isNotEmpty();
    }

    /**
     * Generate a proactive schedule message for a teamleader
     */
    public function generateScheduleMessage(Teamleader $teamleader, array $scheduleItem): string
    {
        $activity = $teamleader->activities()->first();
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
        $currentDate = Carbon::now()->format('l d F Y');
        $currentTime = Carbon::now()->format('H:i');

        // Different message styles based on communication style
        if (str_contains($communicationStyle, 'direct') || str_contains($communicationStyle, 'kort')) {
            return "Hallo team! 👋\n\n**{$title}**\n\n{$description}\n\nZorg dat je erbij bent!";
        }

        if (str_contains($communicationStyle, 'enthousiasmerend') || str_contains($communicationStyle, 'motiverend')) {
            return "Hé team! 🚀 Geweldig nieuws!\n\nWe gaan aan de slag met: **{$title}**\n\n{$description}\n\nIk zie ernaar uit om dit samen met jullie aan te pakken! 💪";
        }

        if (str_contains($communicationStyle, 'data-gedreven') || str_contains($communicationStyle, 'gestructureerd')) {
            return "Team update 📊\n\n**{$title}**\n\n{$description}\n\nZorg voor goede voorbereiding en punctualiteit.";
        }

        if (str_contains($communicationStyle, 'informeel') || str_contains($communicationStyle, 'casual')) {
            return "Hey iedereen! 😊\n\nWe doen: {$title}\n\n{$description}\n\nTot straks!";
        }

        // Default professional style
        return "Beste team,\n\n**{$title}**\n\n{$description}\n\nGraag zie ik jullie allemaal aanwezig.\n\nMet vriendelijke groet,\n{$name}";
    }

    /**
     * Check if a schedule message should be shown (is currently active)
     */
    public function shouldShowScheduleMessage(Activity $activity): bool
    {
        return $this->hasActiveSchedule($activity);
    }

    /**
     * Get all current active schedule messages for a teamleader
     */
    public function getCurrentScheduleMessages(Teamleader $teamleader): array
    {
        $activity = $teamleader->activities()->first();
        if (!$activity) {
            return [];
        }

        $activeItems = $this->getActiveScheduleItems($activity);
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
     * Get all activities with active schedules
     */
    public function getActivitiesWithActiveSchedules(): Collection
    {
        return Activity::all()->filter(function ($activity) {
            return $this->hasActiveSchedule($activity);
        });
    }
}




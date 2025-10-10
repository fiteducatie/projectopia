<?php

namespace App\Services;

use App\Models\Activity;
use App\Models\Project;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class ScheduleHistoryService
{
    /**
     * Get all schedule items for a project with status information
     */
    public function getScheduleHistory(Activity $activity): Collection
    {
        $schedule = $activity->schedule ?? [];
        $now = Carbon::now();

        return collect($schedule)->map(function ($item, $index) use ($now) {
            $timeFrom = Carbon::parse($item['time_from'] ?? '');
            $timeUntil = Carbon::parse($item['time_until'] ?? '');

            return [
                'id' => $index,
                'title' => $item['title'] ?? 'Geen titel',
                'description' => $item['description'] ?? 'Geen beschrijving',
                'time_from' => $timeFrom,
                'time_until' => $timeUntil,
                'time_from_formatted' => $timeFrom->format('d-m-Y H:i'),
                'time_until_formatted' => $timeUntil->format('d-m-Y H:i'),
                'duration' => $timeFrom->diffForHumans($timeUntil, true),
                'status' => $this->getScheduleStatus($timeFrom, $timeUntil, $now),
                'is_active' => $now->between($timeFrom, $timeUntil),
                'days_ago' => $timeUntil->isPast() ? $timeUntil->diffInDays($now) : null,
            ];
        })->sortByDesc(function ($item) {
            return $item['time_from']->timestamp;
        })->values();
    }

    /**
     * Get schedule status based on time
     */
    private function getScheduleStatus(Carbon $timeFrom, Carbon $timeUntil, Carbon $now): string
    {
        if ($now->between($timeFrom, $timeUntil)) {
            return 'active';
        } elseif ($now->gt($timeUntil)) {
            return 'completed';
        } else {
            return 'upcoming';
        }
    }

    /**
     * Get completed schedule items (past)
     */
    public function getCompletedSchedules(Activity $activity): Collection
    {
        return $this->getScheduleHistory($activity)->filter(function ($item) {
            return $item['status'] === 'completed';
        });
    }

    /**
     * Get upcoming schedule items (future)
     */
    public function getUpcomingSchedules(Activity $activity): Collection
    {
        return $this->getScheduleHistory($activity)->filter(function ($item) {
            return $item['status'] === 'upcoming';
        });
    }

    /**
     * Get active schedule items (current)
     */
    public function getActiveSchedules(Activity $activity): Collection
    {
        return $this->getScheduleHistory($activity)->filter(function ($item) {
            return $item['status'] === 'active';
        });
    }

    /**
     * Get schedule statistics for a project
     */
    public function getScheduleStats(Activity $activity): array
    {
        $history = $this->getScheduleHistory($activity);

        return [
            'total' => $history->count(),
            'completed' => $history->where('status', 'completed')->count(),
            'upcoming' => $history->where('status', 'upcoming')->count(),
            'active' => $history->where('status', 'active')->count(),
            'last_activity' => $history->where('status', 'completed')->first(),
            'next_activity' => $history->where('status', 'upcoming')->first(),
        ];
    }

    /**
     * Get schedule history with teamleader messages
     */
    public function getScheduleHistoryWithMessages(Activity $activity): Collection
    {
        $history = $this->getScheduleHistory($activity);
        $scheduleService = app(ScheduleService::class);

        return $history->map(function ($item) use ($activity, $scheduleService) {
            $item['teamleaders'] = $activity->teamleaders->map(function ($teamleader) use ($item, $scheduleService) {
                // Generate what the teamleader would have said
                $mockScheduleItem = [
                    'title' => $item['title'],
                    'description' => $item['description'],
                    'time_from' => $item['time_from']->format('Y-m-d H:i:s'),
                    'time_until' => $item['time_until']->format('Y-m-d H:i:s'),
                ];

                return [
                    'id' => $teamleader->id,
                    'name' => $teamleader->name,
                    'avatar_url' => $teamleader->avatar_url,
                    'message' => $scheduleService->generateScheduleMessage($teamleader, $mockScheduleItem),
                    'timestamp' => $item['time_from']->toISOString(),
                    'communication_style' => $teamleader->communication_style,
                ];
            });

            return $item;
        });
    }
}

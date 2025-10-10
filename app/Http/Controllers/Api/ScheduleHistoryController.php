<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Project;
use App\Services\ScheduleHistoryService;
use Illuminate\Http\Request;

class ScheduleHistoryController extends Controller
{
    protected ScheduleHistoryService $scheduleHistoryService;

    public function __construct(ScheduleHistoryService $scheduleHistoryService)
    {
        $this->scheduleHistoryService = $scheduleHistoryService;
    }

    /**
     * Get schedule history for a project
     */
    public function getHistory(Request $request, Activity $activity)
    {
        $withMessages = $request->boolean('with_messages', false);

        if ($withMessages) {
            $history = $this->scheduleHistoryService->getScheduleHistoryWithMessages($activity);
        } else {
            $history = $this->scheduleHistoryService->getScheduleHistory($activity);
        }

        return response()->json([
            'success' => true,
            'data' => $history,
            'stats' => $this->scheduleHistoryService->getScheduleStats($activity),
        ]);
    }

    /**
     * Get completed schedules only
     */
    public function getCompleted(Activity $activity)
    {
        $completed = $this->scheduleHistoryService->getCompletedSchedules($activity);

        return response()->json([
            'success' => true,
            'data' => $completed,
        ]);
    }

    /**
     * Get upcoming schedules only
     */
    public function getUpcoming(Activity $activity)
    {
        $upcoming = $this->scheduleHistoryService->getUpcomingSchedules($activity);

        return response()->json([
            'success' => true,
            'data' => $upcoming,
        ]);
    }

    /**
     * Get active schedules only
     */
    public function getActive(Activity $activity)
    {
        $active = $this->scheduleHistoryService->getActiveSchedules($activity);

        return response()->json([
            'success' => true,
            'data' => $active,
        ]);
    }

    /**
     * Get schedule statistics
     */
    public function getStats(Activity $activity)
    {
        $stats = $this->scheduleHistoryService->getScheduleStats($activity);

        return response()->json([
            'success' => true,
            'data' => $stats,
        ]);
    }
}

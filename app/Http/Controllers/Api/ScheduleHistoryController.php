<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
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
    public function getHistory(Request $request, Project $project)
    {
        $withMessages = $request->boolean('with_messages', false);

        if ($withMessages) {
            $history = $this->scheduleHistoryService->getScheduleHistoryWithMessages($project);
        } else {
            $history = $this->scheduleHistoryService->getScheduleHistory($project);
        }

        return response()->json([
            'success' => true,
            'data' => $history,
            'stats' => $this->scheduleHistoryService->getScheduleStats($project),
        ]);
    }

    /**
     * Get completed schedules only
     */
    public function getCompleted(Project $project)
    {
        $completed = $this->scheduleHistoryService->getCompletedSchedules($project);

        return response()->json([
            'success' => true,
            'data' => $completed,
        ]);
    }

    /**
     * Get upcoming schedules only
     */
    public function getUpcoming(Project $project)
    {
        $upcoming = $this->scheduleHistoryService->getUpcomingSchedules($project);

        return response()->json([
            'success' => true,
            'data' => $upcoming,
        ]);
    }

    /**
     * Get active schedules only
     */
    public function getActive(Project $project)
    {
        $active = $this->scheduleHistoryService->getActiveSchedules($project);

        return response()->json([
            'success' => true,
            'data' => $active,
        ]);
    }

    /**
     * Get schedule statistics
     */
    public function getStats(Project $project)
    {
        $stats = $this->scheduleHistoryService->getScheduleStats($project);

        return response()->json([
            'success' => true,
            'data' => $stats,
        ]);
    }
}

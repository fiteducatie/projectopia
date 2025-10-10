<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Team;
use Illuminate\Http\Request;

class ChooseActivityController extends Controller
{
    public function index()
    {
        return redirect()->route('choose.teams');
    }

    public function teams()
    {
        $teams = Team::withCount('activities')->orderBy('name')->get();
        return view('choose.teams', compact('teams'));
    }

    public function teamActivities(Team $team)
    {
        $activities = $team->activities()->latest()->get();
        return view('choose.activities', compact('team', 'activities'));
    }

    public function activity(Activity $activity)
    {
        $activity->load('personas');
        return view('choose.activity', compact('activity'));
    }

    public function toggleStatus(Request $request, Activity $activity)
    {
        // Check if user is authenticated and has Admin role
        if (!$request->user() || !$request->user()->hasRole('Admin')) {
            abort(403, 'Unauthorized');
        }

        // Toggle the activity status
        $activity->status = $activity->status === 'closed' ? 'open' : 'closed';
        $activity->save();

        return redirect()->back()->with('success', 'Activity status updated successfully');
    }
}




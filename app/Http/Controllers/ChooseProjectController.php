<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Team;
use Illuminate\Http\Request;

class ChooseProjectController extends Controller
{
    public function index()
    {
        return redirect()->route('choose.teams');
    }

    public function teams()
    {
        $teams = Team::withCount('projects')->orderBy('name')->get();
        return view('choose.teams', compact('teams'));
    }

    public function teamProjects(Team $team)
    {
        $projects = $team->projects()->latest()->get();
        return view('choose.projects', compact('team', 'projects'));
    }

    public function project(Project $project)
    {
        $project->load('personas');
        return view('choose.project', compact('project'));
    }

    public function toggleStatus(Request $request, Project $project)
    {
        // Check if user is authenticated and has Admin role
        if (!$request->user() || !$request->user()->hasRole('Admin')) {
            abort(403, 'Unauthorized');
        }

        // Toggle the project status
        $project->status = $project->status === 'closed' ? 'open' : 'closed';
        $project->save();

        return redirect()->back()->with('success', 'Project status updated successfully');
    }
}



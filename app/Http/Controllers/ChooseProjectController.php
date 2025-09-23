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
}



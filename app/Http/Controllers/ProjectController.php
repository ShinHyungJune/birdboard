<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Project;

class ProjectController extends Controller
{
    public function index(Project $project)
    {
        $projects = auth()->user()->projects;

        return view('projects.index', compact('projects'));
    }

    public function create()
    {
        return view('projects.create');
    }

    public function store()
    {

        // 이제 Request $request 없이 바로 request()로 접근 가능
        $attributes = request()->validate([
            'title' => 'required',
            'body' => 'required',
        ]);

        auth()->user()->projects()->create($attributes);

        return redirect('/projects');
    }

    public function show(Project $project)
    {

        if(auth()->user()->isNot($project->user)){
            abort(403);
        }

        return view('projects.show', compact('project'));
    }
}

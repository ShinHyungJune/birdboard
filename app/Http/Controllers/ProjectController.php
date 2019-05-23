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

    public function show(Project $project)
    {
        $this->authorize('view', $project);

        return view('projects.show', compact('project'));
    }

    public function edit(Project $project)
    {
        $this->authorize('update', $project);

        return view('projects.edit', compact('project'));
    }

    public function update(Project $project)
    {
        $this->authorize('update', $project);

        $project->update($this->validateRequest());

        return redirect($project->path());
    }

    public function create()
    {
        return view('projects.create');
    }

    public function store()
    {
        auth()->user()->projects()->create($this->validateRequest());

        return redirect('/projects');
    }

    public function destroy(Project $project)
    {
        $this->authorize('delete', $project);

        $project->delete();

        return redirect('/projects');
    }

    protected function validateRequest()
    {
        // 이제 Request $request 없이 바로 request()로 접근 가능
        $attributes = request()->validate([
            'title' => 'required',
            'body' => 'required',
        ]);

        return $attributes;
    }

}

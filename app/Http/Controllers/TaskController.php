<?php

namespace App\Http\Controllers;

use App\Task;
use Illuminate\Http\Request;
use App\Project;
class TaskController extends Controller
{
    public function create()
    {
        $project_id = \request()->project_id;

        return view('tasks.create', compact('project_id'));
    }

    public function store()
    {
        request()->validate([
            "body" => 'required',
            "project_id" => 'required'
        ]);

        $project = Project::findOrFail(\request()->project_id);

        if($project->user_id != auth()->id())
            return abort(403);

        Task::create(\request()->all());

        return redirect($project->path());
    }

    public function update(Task $task)
    {
        dd($task->body);
        request()->validate([
            'body' => 'required|string',
            'project_id' => 'required'
        ]);

        $project = Project::findOrFail(\request()->project_id);

        if($project->user_id != auth()->id())
            return abort(403);


        $task->update(request()->all());

        return redirect()->back();
    }
}

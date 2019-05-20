@extends('layouts.app')
@section('content')
    <main>
        <div class="projects__show">
            <div class="box_type01">
                <h1 class="projects__show__title title_type01">
                    {{$project->title}}
                </h1>
                <div class="project__show__body body_type01">
                    {{$project->body}}
                </div>
                <div class="tasks">
                    @forelse($project->tasks()->get() as $task)
                        <form action="/tasks/{{$task->id}}" method="post">
                            @method('PATCH')
                            @csrf
                            {{$task->body}}
                            <input class="task" name="body" style="color:blue; margin-bottom:0;" value="{{$task->body}}">
                            <input type="checkbox" name="completed" style="float:right;" {{$task->completed ? 'checked' : ''}} onChange="this.form.submit()">
                            <button class="btn bg-accent">수정</button>
                        </form>

                    @empty
                        there is no tasks
                    @endforelse
                </div>
                <a href="/projects" class="btn bg-primary white">Go Back</a>
                <a href="/tasks/create?project_id={{$project->id}}" class="btn bg-primary white">Add Task</a>
            </div>
        </div>
    </main>
@endsection

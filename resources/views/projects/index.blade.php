@extends('layouts.app')
@section('content')
    <a class="btn bg-primary white" href="/projects/create">New Project</a>

    <ul>
        @forelse($projects as $project)
            <a href="{{$project->path()}}" class="project" style="display:block;">
                <h3 class="project__title">{{$project->title}}</h3>

                <div class="project__body">{{$project->body}}</div>
            </a>

        @empty
            <div>No projects yet.</div>
        @endforelse
    </ul>
@endsection

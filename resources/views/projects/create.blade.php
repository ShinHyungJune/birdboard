@extends('layouts.app')
@section('content')
    <form action="/projects" method="post">
        @include('projects.form', ['project' => new App\Project()])
    </form>
@endsection

@extends('layouts.app')
@section('content')
    <form action="{{$project->path()}}" method="post">
        @method('patch')

        @include('projects.form')
    </form>
@endsection

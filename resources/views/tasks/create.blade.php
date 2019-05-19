@extends('layouts.app')
@section('content')
    <form action="/tasks" method="post">
        {{csrf_field()}}
        <input type="text" name="body">
        <input type="hidden" name="project_id" value="{{$project_id}}">
        <button>제출</button>
    </form>
@endsection

@csrf

<input type="text" name="title" value="{{$project->title}}">
<input type="text" name="body" value="{{$project->body}}">
<button class="btn btn-primary">전송</button>

@if($errors->any())
    <div class="field">
        @foreach($errors->all() as $error)
            <li style="color:red;">{{$error}}</li>
        @endforeach
    </div>
@endif
@extends('layouts.appUser')
@section('contentUser')
<br><br><h1>Edit Post</h1>
<form action="{{route('editPost', ['id' => $post->id])}}" method="post">
        @csrf
        <div class="form-group">
            <textarea name="content" id="content" cols="10" rows="3" class="form-control">{{$post->content}}</textarea>
                @error('content')
                    <span class = "text-danger">{{$message}}</span>
                @enderror
            <input type = "submit" class="btn btn-primary" value = "Update"></input>
        </div>
    </form>
@endsection
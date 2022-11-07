@extends('layouts.appUser')
@section('contentUser')
<br><br><h1>Edit Comment</h1>
<form action="{{route('updateComment', ['id' => $comment->id])}}" method="post">
        @csrf
        <input type="hidden" name="comment_id">
        <div class="form-group">
            <textarea name="comment" id="comment" cols="10" rows="3" class="form-control">{{$comment->comment}}</textarea>
                @error('content')
                    <span class = "text-danger">{{$message}}</span>
                @enderror
            <input type = "submit" class="btn btn-primary" value = "Update"></input>
        </div>
    </form>
@endsection
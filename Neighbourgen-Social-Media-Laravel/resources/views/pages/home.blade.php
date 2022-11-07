@extends('layouts.appUser')
@section('contentUser')
<br>
<h1>Welcome to the {{$neighbourhood}} Neighbourhood</h1>
<form action="{{route('addPost')}}" method="post">
    @csrf
    <div class="form-group">
        <label for="content">Create Post</label>
        <textarea name="content" id="content" cols="10" rows="3" class="form-control"></textarea>
            @error('content')
                <span class = "text-danger">{{$message}}</span><br>
            @enderror
        <input type = "submit" class="btn btn-primary" value = "Post"></input>
    </div>
</form>
<h2>Your neighbourhood Feed</h2><br>
@foreach($postDtos as $postDto)
    <div>
        <a href="{{route('post', ['id' => $postDto->post->id])}}" style="text-decoration: none;">
        <div class="card">
            <div class="card-body">
                <h6 class="card-title">{{$postDto->creator->name}}&nbsp;&nbsp;&nbsp;&nbsp;{{$postDto->post->created_at}}</h6>
                
                <h5 class="card-title">{{$postDto->post->content}}</h5>
                {{$postDto->reactions}} Likes {{$postDto->comments}} Comments
            </div>
        </div>
        </a>
        <form action="{{route('addReaction')}}" method="post">
            @csrf
            <input type="hidden" name="post_id" value="{{$postDto->post->id}}">
            @if($postDto->hasReacted == 0)
                <input type="submit" class="btn btn-primary" value="Like">
                <input type="hidden" name="hasReacted" value="false">
            @else
                <input type="submit" class="btn btn-primary" value="Unlike">
                <input type="hidden" name="hasReacted" value="true">
            @endif
        </form>
    </div><br>
@endforeach

@endsection
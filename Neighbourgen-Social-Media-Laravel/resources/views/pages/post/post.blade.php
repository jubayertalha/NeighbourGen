@extends('layouts.appUser')
@section('contentUser')
<br>
    <div class="card">
        <div class="card-body">
            <h6 class="card-title">{{$user->name}}&nbsp;&nbsp;&nbsp;&nbsp;{{$post->created_at}}&nbsp;&nbsp;&nbsp;&nbsp;@if($user->id==$currentUser)<a href="{{route('editPost', ['id' => $post->id])}}">Edit</a>       
            <a href="{{route('deletePost', ['id' => $post->id])}}">Delete</a>@endif</h6>
            <h5 class="card-title">{{$post->content}}</h5>
            {{$reactionCount}} Likes {{$commentCount}} Comments
        </div>
    </div>
        <form action="{{route('addReactionFromPost', ['id' => $post->id])}}" method="post">
        @csrf
        <input type="hidden" name="post_id" value="{{$post->id}}">
        @if($hasReacted == 0)
            <input type="submit" class="btn btn-primary" value="Like">
            <input type="hidden" name="hasReacted" value="false">
        @else
            <input type="submit" class="btn btn-primary" value="Unlike">
            <input type="hidden" name="hasReacted" value="true">
        @endif
        </form>

        <form action="{{route('addComment', ['id' => $post->id])}}" method="post">
        @csrf
        <input type="hidden" name="post_id" value="{{$post->id}}">
        <div class="form-group">
            <label for="comment">Comment</label>
            <textarea name="comment" id="comment" cols="10" rows="3" class="form-control"></textarea>
                @error('comment')
                    <span class = "text-danger">{{$message}}</span><br>
                @enderror
            <input type = "submit" class="btn btn-primary" value = "Comment"></input>
        </div>
        </form>
        <div>
            @foreach($commentDtos as $commentDto)
                <div class="card">
                    <div class="card-body">
                        <h6 class="card-title">{{$commentDto->creator}}&nbsp;&nbsp;&nbsp;&nbsp;{{$commentDto->comment->created_at}} @if($commentDto->comment->user_id==$commentDto->currentUser)<a href="{{route('updateComment', ['id' => $commentDto->comment->id])}}">Edit</a>@endif 
                        @if($commentDto->comment->user_id==$commentDto->currentUser)<a href="{{route('deleteComment', ['id' => $commentDto->comment->id])}}">Delete</a>@endif</h6>
                        <h5 class="card-title">{{$commentDto->comment->comment}}</h5>
                    </div>
                </div>
            @endforeach
        @endsection
        
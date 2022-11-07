@extends('layouts.appUser')
@section('contentUser')

<html>
    <head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    </head>
    <body>
    <div class = "container">
    <br><br>
    <h1>Profile: {{$user->name}}</h1>
    <h3>Neighbourhood: {{$neighbourhood->name}}</h3>
    <h4>Email: {{$user->email}}</h4>
    <form action= "{{route('profileEdit')}}" class = "form-group" method = "get">
            <div class="form-group">
                <button type="submit" class="btn btn-success">Edit Profile</button>
            </div>
    </form><br>
    <h1>Posts</h1>

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


    </div>
    </body>
    </html>
    @endsection
@extends('layouts.appUser')
@section('contentUser')

<html>
    <head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    </head>
    <body>
    <div class = "container">
    <br><br>
     <h1>Edit User Profile</h1>
    <form action= "{{route('updateProfile')}}" class = "form-group" method = "post" enctype="multipart/form-data">
        @csrf
        <div class="container">
            <div class="row ">
        <div class="form-group">
                <input type="text" name="name" value="{{$user->name}}"class="form-control">
                @error('name')
                <span class = "text-danger">{{$message}}</span>
            @enderror
            </div>
            <br>
            <br>
            <div class="form-group">
                <input type="password" name="password" value="{{$user->password}}" class="form-control">
                @error('password')
                <span class = "text-danger">{{$message}}</span>
            @enderror
            </div>
            <br>
            <br>
            <div class="form-group">
                <input type="text" name="email" value="{{$user->email}}"class="form-control">
                @error('email')
                <span class = "text-danger">{{$message}}</span>
            @enderror
            </div>
            <br>
            <div>
            <br>
            <br>
            <div class="form-group">
                <button type="submit" class="btn btn-success">Update</button>
            </div>

        </div>
        </div>
    </form>
    </div>
    </body>
    </html>
    @endsection
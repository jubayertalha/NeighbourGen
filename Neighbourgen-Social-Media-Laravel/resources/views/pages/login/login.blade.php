<html>
    <head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <title>NeighbourGen</title>
    </head>

    <body>
    <div class = "container">
    <br><br>
    <h1>Welcome to NeighbourGen</h1>
    <br><br>
    <h1>User Login</h1><br>
    <form action= "{{route('login')}}" class = "form-group" method = "post">
        {{csrf_field()}}
        <div class ="col-md-4 form-group">
            <span>User Name</span>
            <input type="text" name="userName" <?php if(isset($_COOKIE['userName'])) {echo $_COOKIE['userName'];} ?>
            value = "<?php if(isset($_COOKIE['userName'])) {echo $_COOKIE['userName'];} ?>" class = "form-control">
            @error('userName')
                <span class = "text-danger">{{$message}}</span>
            @enderror
        </div>

        <div class ="col-md-4 form-group">
            <span>Password</span>
            <input type="password" name="password" class = "form-control">
            @error('password')
                <span class = "text-danger">{{$message}}</span>
            @enderror
        </div>
        <div class ="col-md-4 form-group">
            <input type="checkbox" id="rememberMe" name="remember">
  			<label for="rememberMe">Remember Me</label>
        </div>
        @if($error!='1')
            <div class ="col-md-4 form-group">
                <span class = "text-danger">{{$error}}</span>
            </div><br>
        @endif
        <br>
        <input type = "submit" class="btn btn-primary" value = "Login">
        Don't have an account? <a href="{{route('registration')}}">sign-up</a>
    </form>
    </div>
    </body>
</html>
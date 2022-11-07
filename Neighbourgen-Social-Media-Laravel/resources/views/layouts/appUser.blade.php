<html>
    <head>
    <title>NeighbourGen</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    </head>
    <body>
    <div style="margin-left:10px;">
        @include('inc.topNav')
    </div>
        <div class="container">

             <div>
                @yield('contentUser')
            </div>
        </div>
    </body>
</html>
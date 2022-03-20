
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Home - Button Request</title>
    <link rel="stylesheet" href="{{asset('assets/bootstrap/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Catamaran:100,200,300,400,500,600,700,800,900">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Muli">
    <link rel="stylesheet" href="{{asset('assets/fonts/fontawesome-all.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/fonts/font-awesome.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/fonts/simple-line-icons.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/fonts/fontawesome5-overrides.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/Features-Blue.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/Features-Boxed.css')}}">
    

</head>

<body id="page-top" data-bs-spy="scroll" data-bs-target="#mainNav" data-bs-offset="56">
    
    <nav class="navbar navbar-light navbar-expand-lg fixed-top" id="mainNav">
        <div class="container"><a class="navbar-brand" href="#page-top" >Button Request</a><button data-bs-toggle="collapse" data-bs-target="#navbarResponsive" class="navbar-toggler float-end" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation"><i class="fa fa-bars"></i></button>
            <div class="collapse navbar-collapse" id="navbarResponsive">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link
                    @if (Route::currentRouteName()=='home.home')
                        active
                    @endif
                    " href="{{route('home.home')}}">Home</a></li>
                    <li class="nav-item"><a class="nav-link
                    @if (Route::currentRouteName()=='home.messageApp')
                        active
                    @endif
                    " href="{{route('home.messageApp')}}">Message App</a></li>
                    <li class="nav-item"><a class="nav-link
                    @if (Route::currentRouteName()=='home.features')
                        active
                    @endif
                    
                    " href="{{route('home.features')}}">Features</a></li>
                    
                    <li class="nav-item"><a class="nav-link" href="{{URL::to('/');}}/documentation">Docs</a></li>

                    <li class="nav-item"><a class="nav-link" href="{{route('apiDoc')}}">API Docs</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{route('dashboard')}}">Console</a></li>
          
                    
                </ul>
            </div>
           
        </div>
    </nav>
    {{$slot}}
    <footer>
        <div class="container">
            <p>©&nbsp;Button Request 2022. All Rights Reserved.</p>
            <ul class="list-inline">
                <li class="list-inline-item"><a href="#">Privacy</a></li>
                <li class="list-inline-item"><a href="#">Terms</a></li>
                <li class="list-inline-item"><a href="#">FAQ</a></li>
            </ul>
        </div>
    </footer>
    
    <script src="{{asset('assets/bootstrap/js/bootstrap.min.js')}}"></script>
    <script src="{{asset('assets/js/new-age.js')}}"></script>
</body>

</html>

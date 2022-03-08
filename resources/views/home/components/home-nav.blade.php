<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Home - Brand</title>
    <link rel="stylesheet" href="{{asset('assets/bootstrap/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lora:400,700,400italic,700italic">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Cabin:700">
    <link rel="stylesheet" href="{{asset('assets/fonts/fontawesome-all.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/fonts/font-awesome.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/fonts/fontawesome5-overrides.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/Features-Boxed.css')}}">
</head>

    

<body id="page-top" data-bs-spy="scroll" data-bs-target="#mainNav" data-bs-offset="77">
    <nav class="navbar navbar-light navbar-expand-md fixed-top" id="mainNav">
        <div class="container"><a class="navbar-brand" href="#">Button request</a><button data-bs-toggle="collapse" class="navbar-toggler navbar-toggler-right" data-bs-target="#navbarResponsive" type="button" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation" value="Menu"><i class="fa fa-bars"></i></button>
            <div class="collapse navbar-collapse" id="navbarResponsive">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="#"></a></li>
                    <li class="nav-item nav-link"><a class="nav-link active" href="{{route('home')}}">Home</a></li>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link" href="{{route('apiDoc')}}">API Documentation</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{route('dashboard')}}">Console</a></li>
                    <li class="nav-item"></li>
                </ul>
            </div>
        </div>
    </nav>
    {{ $slot }}
    
    <section class="text-center content-section" id="contact">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <h2>Contact us</h2>
                    <p>Feel free to leave us a comment on the<a href="#">&nbsp;Grayscale template overview page</a>&nbsp;to give some feedback about this theme!</p>
                    <ul class="list-inline banner-social-buttons">
                        <li class="list-inline-item">&nbsp;<button class="btn btn-primary btn-lg btn-default" type="button"><i class="fa fa-google-plus fa-fw"></i><span class="network-name">&nbsp; Google+</span></button></li>
                        <li class="list-inline-item">&nbsp;<button class="btn btn-primary btn-lg btn-default" type="button"><i class="fa fa-twitter fa-fw"></i><span class="network-name">&nbsp;Twitter</span></button></li>
                        <li class="list-inline-item">&nbsp;</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
    <div class="map-clean"></div>
    <footer>
        <div class="container text-center">
            <p>Copyright ©&nbsp;Brand 2022</p>
        </div>
    </footer>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/js/grayscale.js"></script>
</body>

</html>
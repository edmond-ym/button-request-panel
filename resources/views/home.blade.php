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
    <header class="masthead" style="background-image:url('assets/img/intro-bg.jpg');">
        <div class="intro-body">
            <div class="container">
                <div class="row" style="margin-right: -95px;margin-left: -95px;">
                    <div class="col-lg-8 mx-auto">
                        <h1 class="brand-heading">Button request</h1><p class="intro-text">Some messages are Common and used repeatedly. Let&#39;s use a button instead of typing to substitute all these tedious action<br /></p><a class="btn btn-link btn-circle" role="button" href="#about"><i class="fa fa-angle-double-down animated"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <section class="features-boxed">
        <div class="container" style="margin: 0px 33px;">
            <div class="intro">
                <h2 class="text-center">Features </h2>
                <p class="text-center"></p>
            </div>
            <div class="row justify-content-center features">
                <div class="col-sm-6 col-md-5 col-lg-4 item">
                    <div class="box"><i class="fas fa-tools icon"></i>
                        <h3 class="name">Message configurable</h3>
                        <p class="description">You may set the message for the corresponding button</p>
                    </div>
                </div>
                <div class="col-sm-6 col-md-5 col-lg-4 item">
                    <div class="box"><i class="fas fa-sitemap icon"></i>
                        <h3 class="name">Device - Button Hierarchy<br></h3>
                        <p class="description">There can be more than 1 button in a device. There can be many devices attach to 1 account<br></p>
                    </div>
                </div>
                <div class="col-sm-6 col-md-5 col-lg-4 item">
                    <div class="box"><i class="fa fa-share-square-o icon"></i>
                        <h3 class="name">device shareable</h3>
                        <p class="description">A device can be shared to other account. The owner of the device may set the sharee's access right of the corresponding device<br></p>
                    </div>
                </div>
                <div class="col-sm-6 col-md-5 col-lg-4 item">
                    <div class="box"><i class="fas fa-mobile-alt icon"></i>
                        <h3 class="name">mobile accessible(Coming soon)</h3>
                        <p class="description">You may use the mobile device to view messages received.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section style="background: rgb(238,244,247);margin-bottom: 0px;">
        <div class="container" style="margin-bottom: 0px;margin-top: 0px;background: rgb(233, 238, 247);">
            <div class="row" style="margin-bottom: 0px;margin-top: 0px;margin-right: 40px;">
                <div class="col" style="background: rgb(233, 238, 247);margin-right: 0px;margin-bottom: 65px;"><img src="assets/img/req_principle.drawio.png" style="width: 100%;margin-right: 0px;"></div>
            </div>
        </div>
    </section>
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
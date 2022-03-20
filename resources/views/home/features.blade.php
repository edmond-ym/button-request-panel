<x-home-nav>
    <header class="masthead" style="background: linear-gradient(-101deg, #7b4397, #dc2430 46%, var(--bs-purple) 99%);">
        <div class="container h-100">
            <div class="row h-100">
                <div class="col-lg-7 my-auto" style="width: 100%;">
                    <div class="mx-auto header-content">
                        <h1 class="mb-5">Some messages are Common and used repeatedly. Let's use a button instead of typing to substitute all these tedious action</h1>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <section class="features-boxed" style="background: linear-gradient(var(--bs-gray-100), white 16%, var(--bs-indigo) 100%), var(--bs-blue);">
        <div class="container">
            <div class="intro">
                <h2 class="text-center">Features </h2>
                <p class="text-center"></p>
            </div>
            <div class="row justify-content-center features">
                <div class="col-sm-6 col-md-5 col-lg-4 item">
                    <div class="box" style="height: 350px;"><i class="fas fa-tools icon"></i>
                        <h3 class="name">Message Configurable</h3>
                        <p class="description">You may set the message for the corresponding button</p>
                        <a class="learn-more" target="_blank" href="{{URL::to('/');}}/documentation">Learn more »</a>
                    </div>
                </div>
                <div class="col-sm-6 col-md-5 col-lg-4 item">
                    <div class="box" style="height: 350px;"><i class="fas fa-sitemap icon"></i>
                        <h3 class="name">Device - Button Hierarchy</h3>
                        <p class="description">There can be more than 1 button in a device. There can be many devices attached to 1 account</p>
                        <a class="learn-more" target="_blank" href="{{URL::to('/');}}/documentation">Learn more »</a>
                    </div>
                </div>
                <div class="col-sm-6 col-md-5 col-lg-4 item">
                    <div class="box" style="height: 350px;"><i class="fa fa-share-square-o icon"></i>
                        <h3 class="name">Device Shareable</h3>
                        <p class="description">A Device can be shared to other registered account. The owner of the device may set the sharee's access right of the corresponding device.</p>
                        <a class="learn-more" target="_blank" href="{{URL::to('/');}}/documentation">Learn more »</a>
                    </div>
                </div>
                <div class="col-sm-6 col-md-5 col-lg-4 item">
                    <div class="box" style="height: 350px;"><i class="fa fa-mobile-phone icon"></i>
                        <h3 class="name">PWA App Available</h3>
                        <p class="description">&nbsp;It allows you to manage the messages received of your account.&nbsp;</p>
                        <a class="learn-more" target="_blank" href="{{route('home.messageApp')}}">Learn more »</a>
                    </div>
                </div>
                <div class="col-sm-6 col-md-5 col-lg-4 item">
                    <div class="box" style="height: 350px;"><i class="fas fa-cloud icon"></i>
                        <h3 class="name">API available</h3>
                        <p class="description">It allows you to do further extension.</p>
                        <a class="learn-more" target="_blank"  href="{{route('apiDoc')}}">Learn more »</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section>
        <picture><img src="assets/img/req_principle.drawio.png" style="width: 100%;"></picture>
    </section>
</x-home-nav>
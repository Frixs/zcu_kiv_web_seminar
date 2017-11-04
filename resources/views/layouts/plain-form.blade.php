<html>
<head>
    @include('includes.head')
</head>
<body>
    @include('includes.initials')
    
    <header class="row">
        @include('includes.header')
    </header>

    <div class="container">
        <div class="form-middle-wrapper jumbotron position-middle col-xs-11 col-sm-8 col-lg-5 col-lg-form-middle-max">
        <a href="home"><img class="logo img-responsive" src="{{ instance('Config')::get('app.root_images_rel') }}logo/app_logo_color.png" alt="logo" draggable="false"></a>
            @yield('content')
        </div>
    </div>

    <footer class="row">
        @include('includes.footer')
    </footer>
</body>
</html>
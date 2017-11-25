<html>
<head>
    @include('includes.head')
</head>
<body>
    @include('includes.initials')

    <div class="container">
        <div class="col-xs-11 col-sm-8 col-lg-5 col-lg-form-middle-max form-middle-wrapper">
            <a href="{{ instance('Config')::get('app.root_rel') }}/home"><img class="logo img-responsive" src="{{ instance('Config')::get('app.root_images_rel') }}/logo/app_logo_color.png" alt="logo" draggable="false"></a>
            <div class="jumbotron col-xs-12">
                @yield('content')
            </div>
            <div class="gc-cleaner"></div>
            @include('includes.footer-plain-form')
        </div>
    </div>
</body>
</html>
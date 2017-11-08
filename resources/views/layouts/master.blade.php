<html>
    <head>
        @include('includes.head')
    </head>
    <body>
        @include('includes.initials')
        
        <div class="col-md-2 master-wrapper-left">
            <div class="logo-wrapper">
                <a href="home"><img class="logo img-responsive" src="{{ instance('Config')::get('app.root_images_rel') }}logo/app_logo_color_shine.png" alt="logo" draggable="false"></a>
            </div>
            <footer>
            </footer>
        </div>
        <div class="col-md-10 master-wrapper-right">
            <div class="col-xs-12 header-wrapper">
                <header>
                    <h1>@yield('header')</h1>
                </header>
            </div>
            <div class="col-xs-12 content-wrapper">
                @yield('content')
            </div>
        </div>

        {{--
        <header class="row">
            @include('includes.header')
        </header>

        @section('sidebar')
        @show

        <div class="container">
            @yield('content')
        </div>

        <footer class="row">
            @include('includes.footer')
        </footer>
        --}}
    </body>
</html>
<html>
    <head>
        @include('includes.head')
    </head>
    <body>
        @include('includes.initials')
        
        <div class="col-md-2">
            <footer>
            </footer>
        </div>
        <div class="col-md-10">
            <div class="col-xs-12">
                <header>
                    @yield('header')
                </header>
            </div>
            <div class="col-xs-12">
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
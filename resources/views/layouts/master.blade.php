<html>
    <head>
        @include('includes.head')
    </head>
    <body>
        @include('includes.initials')
        
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
    </body>
</html>
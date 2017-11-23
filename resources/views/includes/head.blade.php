<meta charset="utf-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>{{ instance('Config')::get('app.name') }} - @yield('title')</title>
<meta name="description" content="ZCU/KIV/WEB/Seminar">
<meta name="author" content="Frixs">

<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<!-- jQuery library -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<!-- Latest compiled JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

<!-- Font Awesome -->
<link rel="stylesheet" href="../resources/assets/fonts/font-awesome/css/font-awesome.min.css">

<!-- Google ReCAPTCHA API -->
<script src='https://www.google.com/recaptcha/api.js'></script>

<!-- Flat-UI styles -->
<!-- http://designmodo.github.io/Flat-UI/docs/components.html -->
<link rel="stylesheet" href="{{ instance('Config')::get('app.root') }}/css/vendor/Flat-UI-2.3.0/dist/css/flat-ui.css">
<!-- Flat-UI JavaScript -->
<script src="{{ instance('Config')::get('app.root') }}/css/vendor/Flat-UI-2.3.0/dist/js/flat-ui.js"></script>

<!-- App CSS -->
<link rel="stylesheet" href="{{ instance('Config')::get('app.root') }}/css/app.css">
<!-- App JS -->
<script>
    var root =  "{{ instance('Config')::get('app.root') }}";
</script>
<script src="{{ instance('Config')::get('app.root') }}/js/app.js"></script>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="theme-color" content="#633927">
<title>@yield('titulo', 'Painel') | Painel Arizona Pet Care</title>
<link rel="icon" type="image/png" href="{{ asset('assets/img/logo.png') }}">
@vite(['resources/css/app.css', 'resources/js/app.js'])
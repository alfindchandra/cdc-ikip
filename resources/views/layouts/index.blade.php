<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')-{{ config('app.ikip') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="icon" href="{{ asset('images/logo-ikip.png') }}" type="image/png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
</head>
<body>
    @yield('content')
</body>
</html>
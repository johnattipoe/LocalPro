<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <link rel="icon" type="image/png" href="{{ asset('logo/localpro-mark.png') }}">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'LocalPro — Volta Region')</title>
    @vite(['resources/css/app.css', 'resources/ts/app/app.ts'])
</head>
<body>
    @yield('content')
</body>
</html>

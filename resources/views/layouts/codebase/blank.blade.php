<!doctype html>
<html lang="{{ LaravelLocalization::getCurrentLocale() }}" class="no-focus">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">

        <title>@yield('title') - TKBARU</title>

        <meta name="description" content="Toko Baru - GitzJoey's Laravel Implementations For General Trading System">
        <meta name="author" content="GitzJoey">
        <meta name="robots" content="noindex, nofollow">

        <meta property="og:title" content="TKBARU, Toko, Baru, GitzJoey, Laravel, Implementations, General, Trading, System">
        <meta property="og:site_name" content="TKBARU">
        <meta property="og:description" content="Toko Baru - GitzJoey's Laravel Implementations For General Trading System">
        <meta property="og:type" content="website, app, trading, system">
        <meta property="og:url" content="">
        <meta property="og:image" content="">

        <meta name="csrf-token" content="{{ csrf_token() }}">

        <link rel="shortcut icon" href="{{ asset('images/favicon.ico') }}">

        <link rel="stylesheet" id="css-main" href="{{ mix('css/codebase/main.css') }}">

        @yield('custom_css')
    </head>

    <body>
        @yield('content')

        <input type="hidden" id="appSettings" value=""/>

        <script src="{{ mix('js/codebase/main.js') }}"></script>

        @yield('custom_js')
    </body>
</html>
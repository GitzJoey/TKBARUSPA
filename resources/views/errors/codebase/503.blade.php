<!doctype html>
<html lang="{{ LaravelLocalization::getCurrentLocale() }}" class="no-focus">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">

        <title>503 - TKBARU</title>

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
    </head>

    <body>
        <div id="page-container" class="main-content-boxed">
            <main id="main-container">
                <div class="hero bg-white">
                    <div class="hero-inner">
                        <div class="content content-full">
                            <div class="py-30 text-center">
                                <div class="display-3 text-elegance">
                                    <i class="fa fa-database"></i> 503
                                </div>
                                <h1 class="h2 font-w700 mt-30 mb-10">Oops.. You just found an error page..</h1>
                                <h2 class="h3 font-w400 text-muted mb-50">We are sorry but our service is currently not available..</h2>
                                <a class="btn btn-hero btn-rounded btn-alt-secondary" href="#" onclick="window.history.go(-1); return false;">
                                    <i class="fa fa-arrow-left mr-10"></i> Back to all Errors
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>

        <div id="goTop"></div>

        <input type="hidden" id="appSettings" value="{{ Config::get('session.lifetime') }}-0"/>

        <script src="{{ mix('js/codebase/main.js') }}"></script>

        @yield('custom_js')
    </body>
</html>
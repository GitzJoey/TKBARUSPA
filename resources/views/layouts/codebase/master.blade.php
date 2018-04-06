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

        @if (!empty(Auth::user()->company->ribbon))
            @if (Auth::user()->company->ribbon == 'corporate')
                <link rel="stylesheet" id="css-theme" href="{{ asset('css/codebase/themes/corporate.css') }}">
            @elseif (Auth::user()->company->ribbon == 'earth')
                <link rel="stylesheet" id="css-theme" href="{{ asset('css/codebase/themes/earth.css') }}">
            @elseif (Auth::user()->company->ribbon == 'elegance')
                <link rel="stylesheet" id="css-theme" href="{{ asset('css/codebase/themes/elegance.css') }}">
            @elseif (Auth::user()->company->ribbon == 'flat')
                <link rel="stylesheet" id="css-theme" href="{{ asset('css/codebase/themes/flat.css') }}">
            @elseif (Auth::user()->company->ribbon == 'pulse')
                <link rel="stylesheet" id="css-theme" href="{{ asset('css/codebase/themes/pulse.css') }}">
            @else
                <link rel="stylesheet" id="css-theme" href="{{ asset('css/codebase/themes/default.css') }}">
            @endif
        @endif

        @yield('custom_css')
    </head>

    <body>
        <div id="page-loader" class="show"></div>
        <div id="page-container" class="sidebar-o sidebar-inverse side-scroll">
            <div>
                @include('layouts.codebase.sideoverlay')

                @include('layouts.codebase.sidebar')

                @include('layouts.codebase.header')

                <main id="main-container">
                    <div class="content">
                        <h1 class="content-heading">
                            <div class="row">
                                <div class="col-6">
                                    <strong>@yield('page_title')</strong><small style="font-size: x-small;">@yield('page_title_desc')</small>
                                </div>
                                <div class="col-6">
                                    <div class="pull-right"><small>@yield('breadcrumbs')</small></div>
                                </div>
                            </div>
                        </h1>
                        @yield('content')
                    </div>
                </main>

                @include('layouts.codebase.footer')
            </div>
        </div>

        <div id="goTop"></div>

        <input type="hidden" id="appSettings" value="{{ Config::get('session.lifetime') }}-0"/>

        <script src="{{ mix('js/codebase/main.js') }}"></script>

        @yield('custom_js')
    </body>
</html>
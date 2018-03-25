<!doctype html>
<html lang="{{ LaravelLocalization::getCurrentLocale() }}" class="no-focus">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">

        <title>Login - TKBARU</title>

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

        <link rel="stylesheet" id="css-main" href="{{ mix('css/codebase/codebase.css') }}">
        <link rel="stylesheet" id="css-theme" href="{{ asset('css/codebase/themes/corporate.min.css') }}">

        @yield('custom_css')
    </head>
    <body>
        <div id="LoginVue">
            <div id="page-container" class="main-content-boxed">
                <main id="main-container">
                    <div class="bg-gd-dusk">
                        <div class="hero-static content content-full bg-white invisible" data-toggle="appear">
                            <div class="py-30 px-5 text-center">
                                <img src="{{ asset('images/loginlogo_notext.png') }}" width="125" height="100"/>
                            </div>
                            <div class="py-30 px-5 text-center">
                                <h2 class="h4 font-w400 text-muted mb-0">@lang('login.title')</h2>
                            </div>

                            <div class="row justify-content-center px-5">
                                <div class="col-sm-8 col-md-6 col-xl-4">
                                    <form action="{{ url('/login') }}" method="post">
                                        @csrf
                                        <div class="form-group row">
                                            <div class="col-12">
                                                <div class="form-material floating">
                                                    <input type="text" class="form-control" id="email" name="email">
                                                    <label for="email">@lang('login.email')</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-12">
                                                <div class="form-material floating">
                                                    <input type="password" class="form-control" id="password" name="password">
                                                    <label for="login-password">@lang('login.password')</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-12">
                                                <label class="css-control css-control-secondary css-checkbox css-checkbox-rounded">
                                                    <input type="checkbox" name="remember" class="css-control-input">
                                                    <span class="css-control-indicator"></span> @lang('login.remember_me')
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group row gutters-tiny">
                                            <div class="col-12 mb-10">
                                                <button type="submit" class="btn btn-block btn-hero btn-noborder btn-rounded btn-alt-primary">
                                                    <i class="fa fa-sign-in mr-10"></i> @lang('buttons.login_button')
                                                </button>
                                            </div>
                                            <div class="col-sm-6 mb-5">
                                                <a class="btn btn-block btn-noborder btn-rounded btn-alt-secondary" href="{{ url('/register') }}">
                                                    <i class="fa fa-plus text-muted mr-5"></i> @lang('login.register.new')
                                                </a>
                                            </div>
                                            <div class="col-sm-6 mb-5">
                                                <a class="btn btn-block btn-noborder btn-rounded btn-alt-secondary" href="{{ url('/forgot') }}">
                                                    <i class="fa fa-warning text-muted mr-5"></i> @lang('login.forgot_pass')
                                                </a>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
        </div>

        <input type="hidden" id="appSettings" value=""/>

        <script src="{{ mix('js/codebase/codebase.js') }}"></script>
    </body>
</html>
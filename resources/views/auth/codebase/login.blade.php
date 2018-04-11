@extends('layouts.codebase.blank')

@section('title')
    Login
@endsection

@section('custom_css')
@endsection

@section('content')
    <div id="page-container" class="main-content-boxed">
        <main id="main-container">
            <div class="bg-gd-dusk">
                <div class="hero-static content content-full bg-white invisible" data-toggle="appear">
                    <div class="py-5 px-5 text-center">
                        <img src="{{ asset('images/loginlogo_notext.png') }}" width="125" height="100"/>
                    </div>
                    <div class="py-30 px-5 text-center">
                        <h2 class="h4 font-w400 text-muted mb-0">@lang('login.title')</h2>
                    </div>

                    <div class="row justify-content-center px-5">
                        <div class="col-sm-8 col-md-6 col-xl-4">
                            <form action="{{ url('/login') }}" method="post">
                                @csrf
                                <div class="form-group {{ $errors->has('email') ? 'is-invalid':'' }}">
                                    <div class="form-material floating input-group">
                                        <input type="text" class="form-control" id="email" name="email" value="{{ old('email') }}">
                                        <label for="email">@lang('login.email')</label>
                                        <div class="input-group-append">
                                            <span class="input-group-text">
                                                <i class="si si-envelope"></i>
                                            </span>
                                        </div>
                                    </div>
                                    @if ($errors->has('email'))
                                        <div class="invalid-feedback">{{ $errors->first('email') }}</div>
                                    @endif
                                </div>
                                <div class="form-group {{ $errors->has('password') ? 'is-invalid':'' }}">
                                    <div class="form-material floating input-group">
                                        <input type="password" class="form-control" id="password" name="password">
                                        <label for="login-password">@lang('login.password')</label>
                                        <div class="input-group-append">
                                            <span class="input-group-text">
                                                <i class="si si-key"></i>
                                            </span>
                                        </div>
                                    </div>
                                    @if ($errors->has('password'))
                                        <div id="password-error" class="invalid-feedback">{{ $errors->first('password') }}</div>
                                    @endif
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
                                            <i class="si si-login mr-10"></i> @lang('buttons.login_button')
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
@endsection

@section('custom_js')
    <script type="application/javascript">

    </script>
@endsection
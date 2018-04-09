@extends('layouts.codebase.blank')

@section('title')
@endsection

@section('custom_css')
@endsection

@section('content')
    <div id="page-container" class="main-content-boxed">
        <main id="main-container">
            <div class="hero bg-white">
                <div class="hero-inner">
                    <div class="content content-full">
                        <div class="py-30 text-center">
                            <div class="display-3 text-corporate">
                                <i class="fa fa-ban"></i> 403
                            </div>
                            <h1 class="h2 font-w700 mt-30 mb-10">Oops.. You just found an error page..</h1>
                            <h2 class="h3 font-w400 text-muted mb-50">We are sorry but you do not have permission to access this page..</h2>
                            <a class="btn btn-hero btn-rounded btn-alt-secondary" href="#" onclick="window.history.go(-1); return false;">
                                <i class="fa fa-arrow-left mr-10"></i> Back to all Errors
                            </a>
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
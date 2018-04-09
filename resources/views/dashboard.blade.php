@extends('layouts.codebase.master')

@section('title')
    @lang('dashboard.title')
@endsection

@section('page_title')
    @lang('dashboard.page_title')
@endsection

@section('page_title_desc')
    @lang('dashboard.page_title_desc')
@endsection

@section('breadcrumbs')
    {!! Breadcrumbs::render('dashboard') !!}
@endsection

@section('content')
    <div class="row invisible" data-toggle="appear">
        <div class="col-6 col-xl-3">
            <a class="block block-rounded block-bordered block-link-shadow" href="javascript:void(0)">
                <div class="block-content block-content-full clearfix">
                    <div class="float-right mt-15 d-none d-sm-block">
                        <i class="fa fa-shopping-bag fa-2x text-primary-light"></i>
                    </div>
                    <div class="font-size-h3 font-w600 text-primary" data-toggle="countTo" data-speed="1000" data-to="1500">0</div>
                    <div class="font-size-sm font-w600 text-uppercase text-muted">Sales</div>
                </div>
            </a>
        </div>
        <div class="col-6 col-xl-3">
            <a class="block block-rounded block-bordered block-link-shadow" href="javascript:void(0)">
                <div class="block-content block-content-full clearfix">
                    <div class="float-right mt-15 d-none d-sm-block">
                        <i class="fa fa-google-wallet fa-2x text-earth-light"></i>
                    </div>
                    <div class="font-size-h3 font-w600 text-earth">$<span data-toggle="countTo" data-speed="1000" data-to="780">0</span></div>
                    <div class="font-size-sm font-w600 text-uppercase text-muted">Earnings</div>
                </div>
            </a>
        </div>
        <div class="col-6 col-xl-3">
            <a class="block block-rounded block-bordered block-link-shadow" href="javascript:void(0)">
                <div class="block-content block-content-full clearfix">
                    <div class="float-right mt-15 d-none d-sm-block">
                        <i class="fa fa-envelope fa-2x text-elegance-light"></i>
                    </div>
                    <div class="font-size-h3 font-w600 text-elegance" data-toggle="countTo" data-speed="1000" data-to="15">0</div>
                    <div class="font-size-sm font-w600 text-uppercase text-muted">Messages</div>
                </div>
            </a>
        </div>
        <div class="col-6 col-xl-3">
            <a class="block block-rounded block-bordered block-link-shadow" href="javascript:void(0)">
                <div class="block-content block-content-full clearfix">
                    <div class="float-right mt-15 d-none d-sm-block">
                        <i class="fa fa-user fa-2x text-pulse"></i>
                    </div>
                    <div class="font-size-h3 font-w600 text-pulse" data-toggle="countTo" data-speed="1000" data-to="4252">0</div>
                    <div class="font-size-sm font-w600 text-uppercase text-muted">Online</div>
                </div>
            </a>
        </div>
    </div>
    <br>
    <div id="testVue">
        <div class="input-group">
            <flat-pickr v-model="date" v-bind:config="{ wrap: true }" class="form-control">
            </flat-pickr>
            <div class="input-group-btn">
                <button class="btn btn-default" type="button" title="Toggle" data-toggle>
                    <i class="fa fa-calendar">
                        <span aria-hidden="true" class="sr-only">Toggle</span>
                    </i>
                </button>
                <button class="btn btn-default" type="button" title="Clear" data-clear>
                    <i class="fa fa-times">
                        <span aria-hidden="true" class="sr-only">Clear</span>
                    </i>
                </button>
            </div>
        </div>
    </div>
@endsection

@section('custom_js')
    <script type="application/javascript">
        var testVue = new Vue({
            el: '#testVue',
            data: {
                date: ''
            }
        });
    </script>
@endsection
<script>
    import FlatPickr from "vue-flatpickr-component/src/component";
    export default {
        components: {FlatPickr}
    }
</script>
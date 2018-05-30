@extends('layouts.codebase.master')

@section('title')
    @lang('stock_opname.index.title')
@endsection

@section('page_title')
    @lang('stock_opname.index.page_title')
@endsection

@section('page_title_desc')
    @lang('stock_opname.index.page_title_desc')
@endsection

@section('breadcrumbs')

@endsection

@section('content')
    <div id="stockOpnameVue">
        @include ('layouts.common.error')
        <div class="block block-shadow-on-hover block-mode-loading-refresh" id="stockOpnameListBlock">
            <div class="block-header block-header-default">
                <h3 class="block-title">@lang('stock_opname.index.panel.list_panel.title')</h3>
                <div class="block-options">
                    <button type="button" class="btn-block-option" data-toggle="block-option" data-action="fullscreen_toggle"></button>
                    <button type="button" class="btn-block-option" v-on:click="getAllStock">
                        <i class="si si-refresh"></i>
                    </button>
                    <button type="button" class="btn-block-option" data-toggle="block-option" data-action="content_toggle"></button>
                </div>
            </div>
            <div class="block-content">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-vcenter">
                        <thead class="thead-light">
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
                <div class="row items-push-2x text-center text-sm-left">
                    <div class="col-sm-6 col-xl-4">
                        <button type="button" class="btn btn-primary btn-lg btn-circle" v-on:click="createNew" data-toggle="tooltip" data-placement="top" title="{{ Lang::get('buttons.create_new_button') }}">
                            <i class="fa fa-plus fa-fw"></i>
                        </button>
                        &nbsp;&nbsp;&nbsp;
                        <button type="button" class="btn btn-primary btn-lg btn-circle" data-toggle="tooltip" data-placement="top" title="{{ Lang::get('buttons.print_preview_button') }}">
                            <i class="fa fa-print fa-fw"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="block block-shadow-on-hover block-mode-loading-refresh" id="stockOpnameCRUDBlock">
            <div class="block-header block-header-default">
                <h3 class="block-title">
                    <template v-if="mode == 'create'">@lang('stock_opname.index.panel.crud_panel.title_create')</template>
                    <template v-if="mode == 'show'">@lang('stock_opname.index.panel.crud_panel.title_show')</template>
                </h3>
                <div class="block-options">
                    <button type="button" class="btn-block-option" data-toggle="block-option" data-action="fullscreen_toggle"></button>
                    <button type="button" class="btn-block-option" data-toggle="block-option" data-action="content_toggle"></button>
                </div>
            </div>
            <div class="block-content">
                <form id="stockOpnameForm" method="post" v-on:submit.prevent="validateBeforeSubmit">

                </form>
            </div>
        </div>
    </div>
@endsection

@section('ziggy')
    @routes('stock_opname')
@endsection

@section('custom_js')
    <script type="application/javascript">
        var app = new Vue({
            el: '#stockOpnameVue',
            data: {
                stockList: [],
                mode: '',
                stock: { }
            },
            methods: {
                validateBeforeSubmit: function() {

                },
                getAllStock: function() {

                },
                createNew: function() {
                    this.mode = 'create';
                    this.errors.clear();
                    this.unit = this.emptyUnit();
                },
                backToList: function() {
                    this.mode = 'list';
                    this.errors.clear();
                    this.getAllUnit();
                }
            },
            computed: {

            },
            watch: {
                mode: function() {
                    switch (this.mode) {
                        case 'create':
                        case 'show':
                            this.contentPanel('#stockOpnameListBlock', 'CLOSE')
                            this.contentPanel('#stockOpnameCRUDBlock', 'OPEN')
                            break;
                        case 'list':
                        default:
                            this.contentPanel('#stockOpnameListBlock', 'OPEN')
                            this.contentPanel('#stockOpnameCRUDBlock', 'CLOSE')
                            break;
                    }
                }
            }
        });
    </script>
    <script type="application/javascript" src="{{ mix('js/apps/stock_opname.js') }}"></script>
@endsection
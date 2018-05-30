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
                            <tr>
                                <th class="text-center">@lang('stock_opname.index.table.stock_list.header.warehouse')</th>
                                <th class="text-center">@lang('stock_opname.index.table.stock_list.header.product')</th>
                                <th class="text-center">@lang('stock_opname.index.table.stock_list.header.opname_date')</th>
                                <th class="text-center">@lang('stock_opname.index.table.stock_list.header.current_quantity')</th>
                                <th class="text-center">@lang('labels.ACTION')</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(s, sIdx) in stockList">
                                <td>@{{ s.warehouse.name }}</td>
                                <td>@{{ s.product.name }}</td>
                                <td class="text-center">@{{ s.lastOpnameDate }}</td>
                                <td class="text-right">@{{ s.quantityDisplayUnit }}</td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <button class="btn btn-sm btn-secondary" v-on:click="showSelected(sIdx)"><span class="fa fa-info fa-fw"></span></button>
                                    </div>
                                </td>
                            </tr>
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
                    <div class="form-group">
                        <label for="inputWarehouse" class="col-3 col-form-label">@lang('stock_opname.fields.warehouse')</label>
                        <div class="col-md-8">
                            <input id="inputWarehouse" type="text" class="form-control" disabled>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputProduct" class="col-3 col-form-label">@lang('stock_opname.fields.product')</label>
                        <div class="col-md-8">
                            <input id="inputProduct" type="text" class="form-control" disabled>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputOpnameDate"
                               class="col-3 col-form-label">@lang('stock_opname.fields.opname_date')</label>
                        <div class="col-md-8">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputCurrentQuantity" class="col-3 col-form-label">@lang('stock_opname.fields.current_quantity')</label>
                        <div class="col-md-8">
                            <input id="inputCurrentQuantity" type="text" class="form-control" disabled>
                        </div>
                    </div>
                    <div v-bind:class="{ 'form-group row':true, 'is-invalid':errors.has('adjusted_quantity') ? true:false }">
                        <label for="inputAdjustedQuantity"
                               class="col-3 col-form-label">@lang('stock_opname.fields.adjusted_quantity')</label>
                        <div class="col-md-8">
                        </div>
                    </div>
                    <div v-bind:class="{ 'form-group row':true, 'is-invalid':errors.has('reason') ? true:false }">
                        <label for="inputReason" class="col-3 col-form-label">@lang('stock_opname.fields.reason')</label>
                        <div class="col-md-8">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-2 col-form-label" for="inputButton">&nbsp;</label>
                        <div class="col-10">
                            <template v-if="mode == 'create' || mode == 'edit'">
                                <button type="submit" class="btn btn-primary min-width-125">
                                    @lang('buttons.submit_button')
                                </button>
                                <button type="button" class="btn btn-default min-width-125" v-on:click="backToList">
                                    @lang('buttons.cancel_button')
                                </button>
                            </template>
                            <template v-if="mode == 'show'">
                                <button type="button" class="btn btn-default min-width-125" v-on:click="backToList">
                                    @lang('buttons.back_button')
                                </button>
                            </template>
                        </div>
                    </div>
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
            mounted: function() {
                this.mode = 'list';
                this.getAllStock();
            },
            methods: {
                validateBeforeSubmit: function() {

                },
                getAllStock: function() {
                    return new Promise((resolve, reject) => {
                        axios.get(route('api.get.warehouse.stock.all.current.stock').url()).then(response => {
                            this.stockList = response.data;
                            resolve(true);
                        }).catch(e => {
                            this.handleErrors(e);
                            reject(e.response.data.message);
                        });
                    });
                },
                createNew: function() {
                    this.mode = 'create';
                    this.errors.clear();
                    this.unit = this.emptyUnit();
                },
                editSelected: function(index) {

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
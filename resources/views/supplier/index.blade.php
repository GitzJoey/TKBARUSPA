@extends('layouts.codebase.master')

@section('title')
    @lang('supplier.index.title')
@endsection

@section('page_title')
    @lang('supplier.index.page_title')
@endsection

@section('page_title_desc')
    @lang('supplier.index.page_title_desc')
@endsection

@section('breadcrumbs')

@endsection

@section('content')
    <div id="supplierVue">
        @include ('layouts.common.error')
        <div class="block block-shadow-on-hover block-mode-loading-refresh" id="supplierListBlock">
            <div class="block-header block-header-default">
                <h3 class="block-title">@lang('supplier.index.panel.list_panel.title')</h3>
                <div class="block-options">
                    <button type="button" class="btn-block-option" data-toggle="block-option" data-action="fullscreen_toggle"></button>
                    <button type="button" class="btn-block-option" v-on:click="getAllSupplier">
                        <i class="si si-refresh"></i>
                    </button>
                    <button type="button" class="btn-block-option" data-toggle="block-option" data-action="content_toggle"></button>
                </div>
            </div>
            <div class="block-content">
                <div class="row col-4">
                    <input type="text" class="form-control" id="inputSearchSupplier" placeholder="{{ trans('supplier.fields.search_supplier') }}"
                           v-model="search_supplier_query" v-on:change="getAllSupplier"/>
                </div>
                <br>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-vcenter">
                        <thead class="thead-light">
                        <th>@lang('supplier.index.table.supplier_list.header.name')</th>
                        <th>@lang('supplier.index.table.supplier_list.header.address')</th>
                        <th>@lang('supplier.index.table.supplier_list.header.tax_id')</th>
                        <th>@lang('supplier.index.table.supplier_list.header.status')</th>
                        <th>@lang('supplier.index.table.supplier_list.header.remarks')</th>
                        <th class="text-center action-column-width">@lang('labels.ACTION')</th>
                        </thead>
                        <tbody>
                        <template v-if="supplierList.data != 'undefined'">
                            <tr v-for="(s, sIdx) in supplierList.data">
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        </template>
                        <template v-if="supplierList.data == 'undefined'">
                            <tr>
                                <td colspan="6">aaa</td>
                            </tr>
                        </template>
                        </tbody>
                    </table>
                </div>
                <br>
                <div class="row items-push-2x text-center text-sm-left">
                    <div class="col-6">
                        <button type="button" class="btn btn-primary btn-lg btn-circle" v-on:click="createNew" data-toggle="tooltip" data-placement="top" title="{{ Lang::get('buttons.create_new_button') }}">
                            <i class="fa fa-plus fa-fw"></i>
                        </button>
                        &nbsp;&nbsp;&nbsp;
                        <button type="button" class="btn btn-primary btn-lg btn-circle" data-toggle="tooltip" data-placement="top" title="{{ Lang::get('buttons.print_preview_button') }}">
                            <i class="fa fa-print fa-fw"></i>
                        </button>
                    </div>
                    <div class="col-6">
                        <div class="pull-right">
                            <pagination v-bind:data="supplierList" v-on:pagination-change-page="getAllSupplier"></pagination>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="block block-shadow-on-hover" id="supplierCRUDBlock">
            <div class="block-header block-header-default">
                <h3 class="block-title">
                    <template v-if="mode == 'create'">@lang('supplier.index.panel.crud_panel.title_create')</template>
                    <template v-if="mode == 'show'">@lang('supplier.index.panel.crud_panel.title_show')</template>
                    <template v-if="mode == 'edit'">@lang('supplier.index.panel.crud_panel.title_edit')</template>
                </h3>
                <div class="block-options">
                    <button type="button" class="btn-block-option" data-toggle="block-option" data-action="fullscreen_toggle"></button>
                    <button type="button" class="btn-block-option" data-toggle="block-option" data-action="content_toggle"></button>
                </div>
            </div>
            <div class="block-content">
                <form id="supplierForm" method="post" v-on:submit.prevent="validateBeforeSubmit">
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

@section('custom_js')
    <script type="application/javascript">
        var supplierVue = new Vue ({
            el: '#supplierVue',
            data: {
                supplier: {},
                supplierList: [],
                statusDDL: [],
                mode: '',
                search_supplier_query: '',
                active_page: 0
            },
            mounted: function () {
                this.mode = 'list';
            },
            methods: {
                validateBeforeSubmit: function() {
                    this.$validator.validateAll().then(isValid => {
                        if (!isValid) return;
                        Codebase.blocks('#supplierCRUDBlock', 'state_toggle');
                        if (this.mode == 'create') {
                            axios.post('/api/post/supplier/save',
                                new FormData($('#supplierForm')[0]),
                                { headers: { 'content-type': 'multipart/form-data' } }).then(response => {
                                this.backToList();
                            }).catch(e => { this.handleErrors(e); });
                        } else if (this.mode == 'edit') {
                            axios.post('/api/post/supplier/edit/' + this.supplier.hId,
                                new FormData($('#supplierForm')[0]),
                                { headers: { 'content-type': 'multipart/form-data' } }).then(response => {
                                this.backToList();
                            }).catch(e => { this.handleErrors(e); });
                        } else { }
                        Codebase.blocks('#supplierCRUDBlock', 'state_toggle');
                    });
                },
                getAllSupplier: function(page) {
                    Codebase.blocks('#supplierListBlock', 'state_toggle');

                    var qS = [];
                    if (this.search_supplier_query) { qS.push({ 'key':'s', 'value':this.search_supplier_query }); }
                    if (page && typeof(page) == 'number') {
                        this.active_page = page;
                        qS.push({ 'key':'page', 'value':page });
                    }

                    axios.get('/api/get/supplier/read' + this.generateQueryStrings(qS)).then(response => {
                        this.supplierList = response.data;
                        Codebase.blocks('#supplierListBlock', 'state_toggle');
                    }).catch(e => { this.handleErrors(e); });
                },
                createNew: function() {
                    this.mode = 'create';
                    this.errors.clear();
                    this.supplier = this.emptySupplier();
                },
                editSelected: function(idx) {
                    this.mode = 'edit';
                    this.errors.clear();
                    this.supplier = this.supplierList.data[idx];
                },
                showSelected: function(idx) {
                    this.mode = 'show';
                    this.errors.clear();
                    this.supplier = this.supplierList.data[idx];
                },
                deleteSelected: function(idx) {
                    axios.post('/api/post/supplier/delete/' + idx).then(response => {
                        this.backToList();
                    }).catch(e => { this.handleErrors(e); });
                },
                backToList: function() {
                    this.mode = 'list';
                    this.errors.clear();

                    if (this.active_page != 0 || this.active_page != 1) {
                        this.getAllSupplier(this.active_page);
                    } else {
                        this.getAllSupplier();
                    }
                },
                emptySupplier: function() {
                    return {
                        hId: '',
                    }
                }
            },
            function: {

            }
        });
    </script>
    <script type="application/javascript" src="{{ mix('js/apps/supplier.js') }}"></script>
@endsection
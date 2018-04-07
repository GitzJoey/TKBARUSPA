@extends('layouts.codebase.master')

@section('title')
    @lang('product.index.title')
@endsection

@section('page_title')
    @lang('product.index.page_title')
@endsection

@section('page_title_desc')
    @lang('product.index.page_title_desc')
@endsection

@section('breadcrumbs')

@endsection

@section('content')
    <div id="productVue">
        @include ('layouts.common.error')
        <div class="block block-shadow-on-hover block-mode-loading-refresh" id="productListBlock">
            <div class="block-header block-header-default">
                <h3 class="block-title">@lang('product.index.panel.list_panel.title')</h3>
                <div class="block-options">
                    <button type="button" class="btn-block-option" data-toggle="block-option" data-action="fullscreen_toggle"></button>
                    <button type="button" class="btn-block-option" v-on:click="getAllProduct">
                        <i class="si si-refresh"></i>
                    </button>
                    <button type="button" class="btn-block-option" data-toggle="block-option" data-action="content_toggle"></button>
                </div>
            </div>
            <div class="block-content">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-vcenter">
                        <thead class="thead-light">
                            <th>@lang('product.index.table.product_list.header.type')</th>
                            <th>@lang('product.index.table.product_list.header.name')</th>
                            <th>@lang('product.index.table.product_list.header.short_code')</th>
                            <th>@lang('product.index.table.product_list.header.description')</th>
                            <th>@lang('product.index.table.product_list.header.status')</th>
                            <th>@lang('product.index.table.product_list.header.remarks')</th>
                            <th class="text-center action-column-width">@lang('labels.ACTION')</th>
                        </thead>
                        <tbody>
                            <tr v-for="(p, pIdx) in productList.data">
                                <td>@{{ p.product_type.name }}</td>
                                <td>@{{ p.name }}</td>
                                <td>@{{ p.short_code }}</td>
                                <td>@{{ p.description }}</td>
                                <td>@{{ p.statusI18n }}</td>
                                <td>@{{ p.remarks }}</td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <button class="btn btn-sm btn-secondary" v-on:click="showSelected(pIdx)"><span class="fa fa-info fa-fw"></span></button>
                                        <button class="btn btn-sm btn-secondary" v-on:click="editSelected(pIdx)"><span class="fa fa-pencil fa-fw"></span></button>
                                        <button class="btn btn-sm btn-secondary" v-on:click="deleteSelected(p.hId)"><span class="fa fa-close fa-fw"></span></button>
                                    </div>
                                </td>
                            </tr>
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
                            <pagination v-bind:data="productList" v-on:pagination-change-page="getAllProduct"></pagination>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="block block-shadow-on-hover" id="productCRUDBlock">
            <div class="block-header block-header-default">
                <h3 class="block-title">
                    <template v-if="mode =='create'">@lang('product.index.panel.crud_panel.title_create')</template>
                    <template v-if="mode =='show'">@lang('product.index.panel.crud_panel.title_show')</template>
                    <template v-if="mode =='edit'">@lang('product.index.panel.crud_panel.title_edit')</template>
                </h3>
                <div class="block-options">
                    <button type="button" class="btn-block-option" data-toggle="block-option" data-action="fullscreen_toggle"></button>
                    <button type="button" class="btn-block-option" data-toggle="block-option" data-action="content_toggle"></button>
                </div>
            </div>
            <div class="block-content">
                <form id="productForm" method="post" v-on:submit.prevent="validateBeforeSubmit">
                    <div v-bind:class="{ 'form-group row':true, 'is-invalid':errors.has('type') }">
                        <label for="inputType" class="col-2 col-form-label">@lang('product.fields.type')</label>
                        <div class="col-10">
                            <select class="form-control"
                                    name="type"
                                    v-validate="'required'"
                                    data-vv-as="{{ trans('product.fields.type') }}">
                                <option value="defaultPleaseSelect">@lang('labels.PLEASE_SELECT')</option>
                                <option v-for="(pt, ptIdx) in prodTypeDDL" v-bind:value="pt.hId">@{{ pt.name }}</option>
                            </select>
                            <span v-show="errors.has('type')" class="invalid-feedback" v-cloak>@{{ errors.first('type') }}</span>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('custom_js')
    <script type="application/javascript">
        var productVue = new Vue ({
            el: '#productVue',
            data: {
                productList: [],
                statusDDL: [],
                prodTypeDDL: [],
                mode: '',
                product: { }
            },
            mounted: function () {
                this.mode = 'list';
                this.getAllProduct();
                this.getLookupStatus();
                this.getProductType();
            },
            methods: {
                validateBeforeSubmit: function() {
                    this.$validator.validateAll().then(isValid => {
                        if (!isValid) return;
                        Codebase.blocks('#productCRUDBlock', 'state_toggle');
                        if (this.mode == 'create') {
                            axios.post('/api/post/product/save', new FormData($('#productForm')[0])).then(response => {
                                this.backToList();
                            }).catch(e => { this.handleErrors(e); });
                        } else if (this.mode == 'edit') {
                            axios.post('/api/post/product/edit/' + this.product.hId, new FormData($('#productForm')[0])).then(response => {
                                this.backToList();
                            }).catch(e => { this.handleErrors(e); });
                        } else { }
                        Codebase.blocks('#productCRUDBlock', 'state_toggle');
                    });
                },
                getAllProduct: function(page) {
                    Codebase.blocks('#productListBlock', 'state_toggle');
                    axios.get('/api/get/product/readAll' + '?page=' + page).then(response => {
                        this.productList = response.data;
                        Codebase.blocks('#productListBlock', 'state_toggle');
                    }).catch(e => { this.handleErrors(e); });
                },
                createNew: function() {
                    this.mode = 'create';
                    this.errors.clear();
                    this.product = this.emptyProduct();
                },
                editSelected: function(idx) {
                    this.mode = 'edit';
                    this.errors.clear();
                    this.product = this.productList[idx];
                },
                showSelected: function(idx) {
                    this.mode = 'show';
                    this.errors.clear();
                    this.product = this.productList[idx];
                },
                deleteSelected: function(idx) {
                    axios.post('/api/post/product/delete/' + idx).then(response => {
                        this.backToList();
                    }).catch(e => { this.handleErrors(e); });
                },
                backToList: function() {
                    this.mode = 'list';
                    this.errors.clear();
                    this.getAllProduct();
                },
                emptyproduct: function() {
                    return {
                        hId: '',
                        name: '',
                        symbol: '',
                        status: '',
                        remarks: ''
                    }
                },
                getLookupStatus: function() {
                    axios.get('/api/get/lookup/byCategory/STATUS').then(
                        response => { this.statusDDL = response.data; }
                    );
                },
                getProductType: function() {
                    axios.get('/api/get/product_type/readAll').then(
                        response => { this.prodTypeDDL = response.data; }
                    );
                }
            },
            watch: {
                mode: function() {
                    switch (this.mode) {
                        case 'create':
                        case 'edit':
                        case 'show':
                            Codebase.blocks('#productListBlock', 'close')
                            Codebase.blocks('#productCRUDBlock', 'open')
                            break;
                        case 'list':
                        default:
                            Codebase.blocks('#productListBlock', 'open')
                            Codebase.blocks('#productCRUDBlock', 'close')
                            break;
                    }
                }
            },
            computed: {
                defaultPleaseSelect: function() {
                    return '';
                }
            }
        });
    </script>
    <script type="application/javascript" src="{{ mix('js/apps/product.js') }}"></script>
@endsection
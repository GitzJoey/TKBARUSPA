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
                <div class="row col-4">
                    <input type="text" class="form-control" id="inputSearchProduct" placeholder="{{ trans('product.fields.search_product') }}"
                           v-model="search_product_query" v-on:change="getAllProduct"/>
                </div>
                <br>
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
                            <template v-if="mode == 'create' || mode == 'edit'">
                                <select class="form-control"
                                        name="type"
                                        v-model="product.productTypeHId"
                                        v-validate="'required'"
                                        data-vv-as="{{ trans('product.fields.type') }}">
                                    <option v-bind:value="defaultPleaseSelect">@lang('labels.PLEASE_SELECT')</option>
                                    <option v-for="(pt, ptIdx) in prodTypeDDL" v-bind:value="pt.hId">@{{ pt.name }}</option>
                                </select>
                                <span v-show="errors.has('type')" class="invalid-feedback" v-cloak>@{{ errors.first('type') }}</span>
                            </template>
                            <template v-if="mode == 'show'">
                                <div class="form-control-plaintext">@{{ product.product_type.name }}</div>
                            </template>
                        </div>
                    </div>
                    <div v-bind:class="{ 'form-group row':true, 'is-invalid': errors.has('category') }">
                        <label for="inputCategory" class="col-2 col-form-label">@lang('product.fields.category')</label>
                        <div class="col-sm-10">
                            <table class="table table-striped table-bordered">
                                <thead class="thead-light">
                                    <tr>
                                        <th width="20%">@lang('product.index.table.category_table.header.code')</th>
                                        <th width="30%">@lang('product.index.table.category_table.header.name')</th>
                                        <th width="40%">@lang('product.index.table.category_table.header.description')</th>
                                        <th width="10%" class="text-center">&nbsp;</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(cat, catIdx) in product.product_categories">
                                        <td v-bind:class="{ 'is-invalid':errors.has('pcat_code_' + catIdx) }">
                                            <input type="hidden" name="cat_level[]" v-bind:value="catIdx">
                                            <template v-if="mode == 'create' || mode == 'edit'">
                                                <input type="text" class="form-control" id="inputCatCode" name="cat_code[]" v-model="cat.code"
                                                       v-validate="'required'" v-bind:data-vv-as="'{{ trans('product.index.table.category_table.header.code') }} ' + (catIdx + 1)"
                                                       v-bind:data-vv-name="'pcat_code_' + catIdx">
                                            </template>
                                            <template v-if="mode == 'show'">
                                                <div class="form-control-plaintext">@{{ cat.code }}</div>
                                            </template>
                                        </td>
                                        <td v-bind:class="{ 'is-invalid':errors.has('pcat_name_' + catIdx) }">
                                            <template v-if="mode == 'create' || mode == 'edit'">
                                                <input type="text" class="form-control" id="inputName" name="cat_name[]" v-model="cat.name"
                                                       v-validate="'required'" v-bind:data-vv-as="'{{ trans('product.index.table.category_table.header.name') }} ' + (catIdx + 1)"
                                                       v-bind:data-vv-name="'pcat_name_' + catIdx">
                                            </template>
                                            <template v-if="mode == 'show'">
                                                <div class="form-control-plaintext">@{{ cat.name }}</div>
                                            </template>
                                        </td>
                                        <td>
                                            <template v-if="mode == 'create' || mode == 'edit'">
                                                <input type="text" class="form-control" id="inputDescription" name="cat_description[]" v-model="cat.description">
                                            </template>
                                            <template v-if="mode == 'show'">
                                                <div class="form-control-plaintext">@{{ cat.description }}</div>
                                            </template>
                                        </td>
                                        <td class="text-center">
                                            <template v-if="mode == 'create' || mode == 'edit'">
                                                <button type="button" class="btn btn-xs btn-danger" v-on:click="removeCategory(catIdx)"><span class="fa fa-close"></span></button>
                                            </template>
                                            <template v-if="mode == 'show'"></template>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <template v-if="mode == 'create' || mode == 'edit'">
                                <button type="button" class="btn btn-xs btn-default" v-on:click="addCategory()">@lang('buttons.create_new_button')</button>
                            </template>
                            <template v-if="mode == 'show'"></template>
                        </div>
                    </div>
                    <div v-bind:class="{ 'form-group row':true, 'is-invalid':errors.has('name') }">
                        <label for="inputName" class="col-2 col-form-label">@lang('product.fields.name')</label>
                        <div class="col-sm-10">
                            <template v-if="mode == 'create' || mode == 'edit'">
                                <input type="text" class="form-control" id="inputName" name="name" placeholder="@lang('product.fields.name')"
                                       v-model="product.name" v-validate="'required'" data-vv-as="{{ trans('product.fields.name') }}">
                                <span v-show="errors.has('name')" class="invalid-feedback" v-cloak>@{{ errors.first('name') }}</span>
                            </template>
                            <template v-if="mode == 'show'">
                                <div class="form-control-plaintext">@{{ product.name }}</div>
                            </template>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputImage" class="col-2 col-form-label">@lang('product.fields.logo')</label>
                        <div class="col-md-10">
                            <template v-if="mode == 'create' || mode == 'edit'">
                                <img class="img-avatar128" v-bind:src="product.image_filename ? this.getCurrentUrl('/images/' + product.image_filename):this.getCurrentUrl('/images/no_image.png')"/>
                                <input type="file" id="inputImage" name="image_filename">
                            </template>
                            <template v-if="mode == 'show'">
                                <img class="img-avatar128" v-bind:src="product.image_filename ? this.getCurrentUrl('/images/' + product.image_filename):this.getCurrentUrl('/images/no_image.png')"/>
                            </template>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputShortCode" class="col-sm-2 col-form-label">@lang('product.fields.short_code')</label>
                        <div class="col-sm-10">
                            <template v-if="mode == 'create' || mode == 'edit'">
                                <input type="text" class="form-control" id="inputShortCode" name="short_code" v-model="product.short_code" placeholder="@lang('product.fields.short_code')">
                            </template>
                            <template v-if="mode == 'show'">
                                <div class="form-control-plaintext">@{{ product.short_code }}</div>
                            </template>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputBarcode" class="col-sm-2 col-form-label">@lang('product.fields.barcode')</label>
                        <div class="col-sm-10">
                            <template v-if="mode == 'create' || mode == 'edit'">
                                <input type="text" class="form-control" id="inputBarcode" name="barcode" v-model="product.barcode" placeholder="@lang('product.fields.barcode')">
                            </template>
                            <template v-if="mode == 'show'">
                                <div class="form-control-plaintext">@{{ product.barcode }}</div>
                            </template>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputDescription" class="col-sm-2 col-form-label">@lang('product.fields.description')</label>
                        <div class="col-sm-10">
                            <template v-if="mode == 'create' || mode == 'edit'">
                                <input type="text" class="form-control" id="inputDescription" name="description" v-model="product.description" placeholder="@lang('product.fields.description')">
                            </template>
                            <template v-if="mode == 'show'">
                                <div class="form-control-plaintext">@{{ product.description }}</div>
                            </template>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputProductUnit" class="col-2 col-form-label">@lang('product.fields.product_unit')</label>
                        <div class="col-md-10">
                            <table class="table table-striped table-bordered">
                                <thead class="thead-light">
                                    <tr>
                                        <th>@lang('product.index.table.product_unit_table.header.unit')</th>
                                        <th class="text-center">@lang('product.index.table.product_unit_table.header.is_base')</th>
                                        <th>@lang('product.index.table.product_unit_table.header.conversion_value')</th>
                                        <th>@lang('product.index.table.product_unit_table.header.remarks')</th>
                                        <th class="text-center">&nbsp;</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(punit, punitIdx) in product.product_units">
                                        <td v-bind:class="{ 'is-invalid':errors.has('unit_' + punitIdx) }">
                                            <template v-if="mode == 'create' || mode == 'edit'">
                                                <select class="form-control"
                                                        name="unit_id[]"
                                                        v-validate="'required'"
                                                        v-model="punit.unitHId"
                                                        v-bind:data-vv-as="'{{ trans('product.index.table.product_unit_table.header.unit') }} ' + (punitIdx + 1)"
                                                        v-bind:data-vv-name="'unit_' + punitIdx">
                                                    <option v-bind:value="defaultPleaseSelect">@lang('labels.PLEASE_SELECT')</option>
                                                    <option v-for="(u, uIdx) in unitDDL" v-bind:value="u.hId">@{{ u.unitName }}</option>
                                                </select>
                                            </template>
                                            <template v-if="mode == 'show'">
                                                <div class="form-control-plaintext">@{{ punit.unit.unitName }}</div>
                                            </template>
                                        </td>
                                        <td class="text-center">
                                            <template v-if="mode == 'create' || mode == 'edit'">
                                                <div class="custom-control custom-checkbox mb-5">
                                                    <input class="custom-control-input" type="checkbox" v-bind:id="'punit_is_base_' + (punitIdx + 1)" v-model="punit.is_base" v-on:change="changeIsBase(punitIdx)">
                                                    <label class="custom-control-label text-primary" v-bind:for="'punit_is_base_' + (punitIdx + 1)"></label>
                                                    <input type="hidden" v-model="punit.is_base_val" name="is_base[]"/>
                                                </div>
                                            </template>
                                            <template v-if="mode == 'show'">
                                                <div class="form-control-plaintext">
                                                    <div class="custom-control custom-checkbox mb-5">
                                                        <input class="custom-control-input" type="checkbox" name="is_base[]" v-model="punit.is_base" v-bind:id="'punit_is_base_' + (punitIdx + 1)" disabled="disabled">
                                                        <label class="custom-control-label text-primary" v-bind:for="'punit_is_base_' + (punitIdx + 1)"></label>
                                                    </div>
                                                </div>
                                            </template>
                                        </td>
                                        <td v-bind:class="{ 'is-invalid':errors.has('conv_val_' + punitIdx) }">
                                            <template v-if="mode == 'create' || mode == 'edit'">
                                                <input type="text" class="form-control" v-model="punit.conversion_value" name="conversion_value[]"
                                                       v-validate="'required'"
                                                       v-bind:readonly="punit.is_base_val == 1"
                                                       v-bind:data-vv-as="'{{ trans('product.index.table.product_unit_table.header.conversion_value') }} ' + (punitIdx + 1)"
                                                       v-bind:data-vv-name="'conv_val_' + punitIdx"/>
                                            </template>
                                            <template v-if="mode == 'show'">
                                                <div class="form-control-plaintext">@{{ punit.conversion_value }}</div>
                                            </template>
                                        </td>
                                        <td>
                                            <template v-if="mode == 'create' || mode == 'edit'">
                                                <input type="text" class="form-control" v-model="punit.remarks" name="punit_remarks[]"/>
                                            </template>
                                            <template v-if="mode == 'show'">
                                                <div class="form-control-plaintext">@{{ punit.remarks }}</div>
                                            </template>
                                        </td>
                                        <td class="text-center">
                                            <template v-if="mode == 'create' || mode == 'edit'">
                                                <button type="button" class="btn btn-xs btn-danger" v-on:click="removeProductUnit(punitIdx)"><span class="fa fa-close"></span></button>
                                            </template>
                                            <template v-if="mode == 'show'">
                                                <div class="form-control-plaintext"></div>
                                            </template>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <template v-if="mode == 'create' || mode == 'edit'">
                                <button type="button" class="btn btn-xs btn-default" v-on:click="addNewProductUnit()">@lang('buttons.create_new_button')</button>
                            </template>
                            <template v-if="mode == 'show'">
                                <div class="form-control-plaintext"></div>
                            </template>
                        </div>
                    </div>
                    <div v-bind:class="{ 'form-group row':true, 'is-invalid':errors.has('minimal_in_stock') }">
                        <label for="inputMinimalInStock" class="col-sm-2 col-form-label">@lang('product.fields.minimal_in_stock')</label>
                        <div class="col-sm-10">
                            <template v-if="mode == 'create' || mode == 'edit'">
                                <input type="text" class="form-control" id="inputMinimalInStock" name="minimal_in_stock"
                                       v-model="product.minimal_in_stock"
                                       v-validate="'required|min_value:0|numeric'" placeholder="@lang('product.fields.minimal_in_stock')"
                                       data-vv-as="{{ trans('product.fields.minimal_in_stock') }}">
                                <span v-show="errors.has('minimal_in_stock')" class="invalid-feedback" v-cloak>@{{ errors.first('minimal_in_stock') }}</span>
                            </template>
                            <template v-if="mode == 'show'">
                                <div class="form-control-plaintext">@{{ product.minimal_in_stock }}</div>
                            </template>
                        </div>
                    </div>
                    <div v-bind:class="{ 'form-group row':true, 'is-invalid':errors.has('status') }">
                        <label for="inputStatus" class="col-2 col-form-label">@lang('product.fields.status')</label>
                        <div class="col-md-10">
                            <template v-if="mode == 'create' || mode == 'edit'">
                                <select class="form-control"
                                        name="status"
                                        v-model="product.status"
                                        v-validate="'required'"
                                        data-vv-as="{{ trans('product.fields.status') }}">
                                    <option v-bind:value="defaultPleaseSelect">@lang('labels.PLEASE_SELECT')</option>
                                    <option v-for="(s, sIdx) in statusDDL" v-bind:value="s.code">@{{ s.description }}</option>
                                </select>
                                <span v-show="errors.has('status')" class="invalid-feedback" v-cloak>@{{ errors.first('status') }}</span>
                            </template>
                            <template v-if="mode == 'show'">
                                <div class="form-control-plaintext">@{{ product.statusI18n }}</div>
                            </template>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputRemarks" class="col-sm-2 control-label">@lang('product.fields.remarks')</label>
                        <div class="col-sm-10">
                            <template v-if="mode == 'create' || mode == 'edit'">
                                <input type="text" class="form-control" id="inputRemarks" name="remarks" v-model="product.remarks" placeholder="@lang('product.fields.remarks')">
                            </template>
                            <template v-if="mode == 'show'">
                                <div class="form-control-plaintext">@{{ product.remarks }}</div>
                            </template>
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

@section('custom_js')
    <script type="application/javascript">
        var productVue = new Vue ({
            el: '#productVue',
            data: {
                productList: [],
                statusDDL: [],
                prodTypeDDL: [],
                unitDDL: [],
                mode: '',
                product: { },
                search_product_query: '',
                active_page: 0
            },
            mounted: function () {
                this.mode = 'list';
                this.getAllProduct();
                this.getLookupStatus();
                this.getProductType();
                this.getUnit();
            },
            methods: {
                validateBeforeSubmit: function() {
                    this.$validator.validateAll().then(isValid => {
                        if (!isValid) return;
                        Codebase.blocks('#productCRUDBlock', 'state_toggle');
                        if (this.mode == 'create') {
                            axios.post('/api/post/product/save',
                                new FormData($('#productForm')[0]),
                                { headers: { 'content-type': 'multipart/form-data' } }).then(response => {
                                this.backToList();
                            }).catch(e => { this.handleErrors(e); });
                        } else if (this.mode == 'edit') {
                            axios.post('/api/post/product/edit/' + this.product.hId,
                                new FormData($('#productForm')[0]),
                                { headers: { 'content-type': 'multipart/form-data' } }).then(response => {
                                this.backToList();
                            }).catch(e => { this.handleErrors(e); });
                        } else { }
                        Codebase.blocks('#productCRUDBlock', 'state_toggle');
                    });
                },
                getAllProduct: function(page) {
                    Codebase.blocks('#productListBlock', 'state_toggle');

                    var qS = [];
                    if (this.search_product_query) { qS.push({ 'key':'p', 'value':this.search_product_query }); }
                    if (page && typeof(page) == 'number') {
                        this.active_page = page;
                        qS.push({ 'key':'page', 'value':page });
                    }

                    axios.get('/api/get/product/read' + this.generateQueryStrings(qS)).then(response => {
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
                    this.product = this.productList.data[idx];

                    for (var i = 0; i < this.product.product_units; i++) {
                        if (this.product.product_units[i].is_base) {
                            this.product.product_units[i].is_base_val = 1;
                        } else {
                            this.product.product_units[i].is_base_val = 0;
                        }
                    }
                },
                showSelected: function(idx) {
                    this.mode = 'show';
                    this.errors.clear();
                    this.product = this.productList.data[idx];
                },
                deleteSelected: function(idx) {
                    axios.post('/api/post/product/delete/' + idx).then(response => {
                        this.backToList();
                    }).catch(e => { this.handleErrors(e); });
                },
                backToList: function() {
                    this.mode = 'list';
                    this.errors.clear();

                    if (this.active_page != 0 || this.active_page != 1) {
                        this.getAllProduct(this.active_page);
                    } else {
                        this.getAllProduct();
                    }
                },
                emptyProduct: function() {
                    return {
                        hId: '',
                        productTypeHId: '',
                        name: '',
                        symbol: '',
                        status: '',
                        remarks: '',
                        product_categories: [],
                        product_units: []
                    }
                },
                addCategory: function() {
                    this.product.product_categories.push({
                        'code':'',
                        'name':'',
                        'description':'',
                        'level':0
                    });
                },
                removeCategory: function(idx) {
                    this.product.product_categories.splice(idx, 1);
                },
                addNewProductUnit: function () {
                    this.product.product_units.push({
                        'unitHId': '',
                        'is_base': false,
                        'is_base_val': 0,
                        'conversion_value': '',
                        'remarks': ''
                    });
                },
                removeProductUnit: function (idx) {
                    this.product.product_units.splice(idx, 1);
                },
                changeIsBase: function (idx) {
                    if (this.product.product_units[idx].is_base) {
                        this.product.product_units[idx].is_base_val = 1;
                        this.product.product_units[idx].conversion_value = '1';
                        for (var i = 0; i < this.product.product_units.length; i++) {
                            if (i == idx) continue;
                            this.product.product_units[i].is_base = !this.product.product_units[idx].is_base;
                            this.product.product_units[i].is_base_val = 0;
                        }
                    }
                },
                getLookupStatus: function() {
                    axios.get('/api/get/lookup/byCategory/STATUS').then(
                        response => { this.statusDDL = response.data; }
                    );
                },
                getProductType: function() {
                    axios.get('/api/get/product_type/read').then(
                        response => { this.prodTypeDDL = response.data; }
                    );
                },
                getUnit: function() {
                    axios.get('/api/get/unit/read').then(
                        response => { this.unitDDL = response.data; }
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
                },
                generatedImageUrl: function(image_filename) {
                    return this.getCurrentUrl + '/' + image_filename;
                }
            }
        });
    </script>
    <script type="application/javascript" src="{{ mix('js/apps/product.js') }}"></script>
@endsection
@extends('layouts.codebase.master')

@section('title')
    @lang('purchase_order.index.title')
@endsection

@section('page_title')
    <span class="fa fa-cart-plus fa-fw"></span>&nbsp;@lang('purchase_order.index.page_title')
@endsection

@section('page_title_desc')
    @lang('purchase_order.index.page_title_desc')
@endsection

@section('breadcrumbs')

@endsection

@section('content')
    <div id="poVue">
        @include ('layouts.common.error')
        <div class="block block-shadow-on-hover block-mode-loading-refresh" id="poListBlock">
            <div class="block-header block-header-default">
                <h3 class="block-title">@lang('purchase_order.index.panel.list_panel.title')</h3>
                <div class="block-options">
                    <button type="button" class="btn-block-option" data-toggle="block-option" data-action="fullscreen_toggle"></button>
                    <button type="button" class="btn-block-option" v-on:click="getAllPO">
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
                                <th class="text-center" width="15%">@lang('purchase_order.index.table.list_table.header.code')</th>
                                <th class="text-center" width="15%">@lang('purchase_order.index.table.list_table.header.po_date')</th>
                                <th class="text-center" width="25%">@lang('purchase_order.index.table.list_table.header.supplier')</th>
                                <th class="text-center" width="15%">@lang('purchase_order.index.table.list_table.header.shipping_date')</th>
                                <th class="text-center" width="20%">@lang('purchase_order.index.table.list_table.header.status')</th>
                                <th class="text-center" width="10%">@lang('labels.ACTION')</th>
                            </tr>
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
        <div class="block block-shadow-on-hover block-mode-loading-refresh" id="poCRUDBlock">
            <div class="block-header block-header-default">
                <h3 class="block-title">
                    <template v-if="mode == 'create'">@lang('purchase_order.index.panel.crud_panel.title_create')</template>
                    <template v-if="mode == 'show'">@lang('purchase_order.index.panel.crud_panel.title_show')</template>
                    <template v-if="mode == 'edit'">@lang('purchase_order.index.panel.crud_panel.title_edit')</template>
                </h3>
                <div class="block-options">
                    <button type="button" class="btn-block-option" data-toggle="block-option" data-action="fullscreen_toggle"></button>
                    <button type="button" class="btn-block-option" data-toggle="block-option" data-action="content_toggle"></button>
                </div>
            </div>
            <div class="block-content">
                <form id="poForm" method="post" v-on:submit.prevent="validateBeforeSubmit">
                    <div class="form-group row">
                        <div class="col-6">
                            <div class="block block-shadow-on-hover block-mode-loading-refresh" id="supplierListBlock">
                                <div class="block-header block-header-default">
                                    <h3 class="block-title">@lang('purchase_order.index.panel.supplier_panel.title')</h3>
                                    <div class="block-options">
                                        <button type="button" class="btn-block-option" data-toggle="block-option" data-action="content_toggle"></button>
                                    </div>
                                </div>
                                <div class="block-content">
                                    <div v-bind:class="{ 'form-group row':true, 'is-invalid':errors.has('supplier_type') }">
                                        <label for="inputSupplierType" class="col-3 col-form-label">@lang('purchase_order.fields.supplier_type')</label>
                                        <div class="col-9">
                                            <select id="inputSupplierType" name="supplier_type" class="form-control"
                                                    v-validate="'required'" data-vv-as="{{ trans('purchase_order.fields.supplier_type') }}"
                                                    v-model="po.supplier_type" v-on:change="onChangeSupplierType(po.supplier_type)">
                                                <option v-bind:value="defaultPleaseSelect">@lang('labels.PLEASE_SELECT')</option>
                                                <option v-for="(st, stIdx) of supplierTypeDDL" v-bind:value="st.code">@{{ st.description }}</option>
                                            </select>
                                            <span v-show="errors.has('supplier_type')" class="invalid-feedback">@{{ errors.first('supplier_type') }}</span>
                                        </div>
                                    </div>
                                    <template v-if="po.supplier_type == 'SUPPLIERTYPE.R'">
                                        <div v-bind:class="{ 'form-group row':true, 'is-invalid':errors.has('supplier_id') }">
                                            <label for="inputSupplierId" class="col-3 col-form-label">@lang('purchase_order.fields.supplier_name')</label>
                                            <div class="col-md-7">
                                                <select id="inputSupplierId" name="supplier_id" class="form-control"
                                                        v-validate="po.supplier_type == 'SUPPLIERTYPE.R' ? 'required':''"
                                                        data-vv-as="{{ trans('purchase_order.fields.supplier_name') }}"
                                                        v-model="po.supplierHId">
                                                    <option v-bind:value="defaultPleaseSelect">@lang('labels.PLEASE_SELECT')</option>
                                                    <option v-for="(supplier, supplierIdx) of supplierDDL" v-bind:value="supplier.hId">@{{ supplier.name }}</option>
                                                </select>
                                                <span v-show="errors.has('supplier_id')" class="help-block" v-cloak>@{{ errors.first('supplier_id') }}</span>
                                            </div>
                                            <div class="col-sm-2">
                                                <button id="supplierDetailButton" type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#supplierDetailModal">
                                                    <span class="fa fa-info-circle fa-lg"></span>
                                                </button>
                                            </div>
                                        </div>
                                    </template>
                                    <template v-if="po.supplier_type == 'SUPPLIERTYPE.WI'">
                                        <div v-bind:class="{ 'form-group row':true, 'is-invalid':errors.has('walk_in_supplier') }">
                                            <label for="inputSupplierName" class="col-3 col-form-label">@lang('purchase_order.fields.supplier_name')</label>
                                            <div class="col-md-9">
                                                <input type="text" id="inputSupplierName" name="walk_in_supplier"
                                                       v-validate="po.supplier_type == 'SUPPLIERTYPE.WI' ? 'required':''"
                                                       data-vv-as="{{ trans('purchase_order.fields.supplier_name') }}"
                                                       class="form-control" v-model="po.supplier_name">
                                                <span v-show="errors.has('walk_in_supplier')" class="invalid-feedback">@{{ errors.first('walk_in_supplier') }}</span>
                                            </div>
                                        </div>
                                        <div v-bind:class="{ 'form-group row':true, 'has-error':errors.has('walk_in_supplier_detail') }">
                                            <label for="inputSupplierDetails" class="col-3 col-form-label">@lang('purchase_order.fields.supplier_details')</label>
                                            <div class="col-md-9">
                                                <textarea id="inputSupplierDetails" name="walk_in_supplier_detail" class="form-control" rows="5"
                                                    v-validate="po.supplier_type == 'SUPPLIERTYPE.WI' ? 'required':''"
                                                    data-vv-as="{{ trans('purchase_order.fields.supplier_details') }}"
                                                    v-model="po.supplier_details"></textarea>
                                                <span v-show="errors.has('walk_in_supplier_detail')" class="invalid-feedback">@{{ errors.first('walk_in_supplier_detail') }}</span>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="block block-shadow-on-hover block-mode-loading-refresh" id="poDetailBlock">
                                <div class="block-header block-header-default">
                                    <h3 class="block-title">@lang('purchase_order.index.panel.detail_panel.title')</h3>
                                    <div class="block-options">
                                        <button type="button" class="btn-block-option" data-toggle="block-option" data-action="content_toggle"></button>
                                    </div>
                                </div>
                                <div class="block-content">
                                    <div class="form-group row">
                                        <label for="inputPoCode" class="col-3 col-form-label">@lang('purchase_order.fields.po_code')</label>
                                        <div class="col-md-9">
                                            <input type="text" class="form-control" id="inputPoCode" name="code" v-model="po.po_code" placeholder="PO Code" readonly>
                                        </div>
                                    </div>
                                    <div v-bind:class="{ 'form-group row':true, 'is-invalid':errors.has('po_type') }">
                                        <label for="inputPoType" class="col-3 col-form-label">@lang('purchase_order.fields.po_type')</label>
                                        <div class="col-md-9">
                                            <select id="inputPoType" name="po_type" class="form-control"
                                                    v-validate="'required'"
                                                    data-vv-as="{{ trans('purchase_order.fields.po_type') }}"
                                                    v-model="po.po_type">
                                                <option v-bind:value="defaultPleaseSelect">@lang('labels.PLEASE_SELECT')</option>
                                                <option v-for="(poType, poTypeIdx) of poTypeDDL" v-bind:value="poType.code">@{{ poType.description }}</option>
                                            </select>
                                            <span v-show="errors.has('po_type')" class="invalid-feedback" v-cloak>@{{ errors.first('po_type') }}</span>
                                        </div>
                                    </div>
                                    <div v-bind:class="{ 'form-group row':true, 'is-invalid':errors.has('po_created') }">
                                        <label for="inputPoCreated" class="col-3 col-form-label">@lang('purchase_order.fields.po_created')</label>
                                        <div class="col-md-9">
                                            <flat-pickr id="inputPoCreated" name="po_created" v-bind:config="flatPickrConfig" v-model="po.po_created" class="form-control" v-validate="'required'" data-vv-as="{{ trans('purchase_order.fields.po_created') }}"></flat-pickr>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="inputPoStatus" class="col-3 col-form-label">@lang('purchase_order.fields.po_status')</label>
                                        <div class="col-sm-9">
                                            <div class="form-control-plaintext">@{{ poStatusDesc }}</div>
                                            <input type="hidden" name="po_status" v-model="po.po_status"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-12">
                            <div class="block block-shadow-on-hover block-mode-loading-refresh" id="transactionListBlock">
                                <div class="block-header block-header-default">
                                    <h3 class="block-title">@lang('purchase_order.index.panel.transaction_panel.title')</h3>
                                    <div class="block-options">
                                        <button type="button" class="btn-block-option" data-toggle="block-option" data-action="content_toggle"></button>
                                    </div>
                                </div>
                                <div class="block-content">
                                    <table id="itemsListTable" class="table table-responsive table-bordered table-hover">
                                        <thead class="thead-light">
                                            <tr>
                                                <th colspan="6">@lang('purchase_order.index.table.item_table.header.product_name')</th>
                                            </tr>
                                            <tr>
                                                <th width="5%"></th>
                                                <th width="10%" class="text-center">@lang('purchase_order.index.table.item_table.header.quantity')</th>
                                                <th width="15%" class="text-center">@lang('purchase_order.index.table.item_table.header.unit')</th>
                                                <th width="15%" class="text-center">@lang('purchase_order.index.table.item_table.header.price_unit')</th>
                                                <th width="5%">&nbsp;</th>
                                                <th width="50%" class="text-center">@lang('purchase_order.index.table.item_table.header.total_price')</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
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
    @routes('purchase_order')
@endsection

@section('custom_js')
    <script type="application/javascript">
        var poVue = new Vue ({
            el: '#poVue',
            data: {
                poList: [],
                supplierTypeDDL: [],
                poTypeDDL: [],
                supplierDDL: [],
                selectedSupplier: {},
                poStatusDesc: '',
                statusDDL: [],
                mode: '',
                po: { }
            },
            mounted: function () {
                this.mode = 'list';
                this.getAllPO();
                this.getSupplier();
                this.getSupplierType();
                this.getPOType();
            },
            methods: {
                validateBeforeSubmit: function() {
                    this.$validator.validateAll().then(isValid => {
                        if (!isValid) return;
                        Codebase.blocks('#poCRUDBlock', 'state_toggle');
                        if (this.mode == 'create') {
                            axios.post('/api/post/po/save', new FormData($('#poForm')[0])).then(response => {
                                this.backToList();
                            }).catch(e => { this.handleErrors(e); });
                        } else if (this.mode == 'edit') {
                            axios.post('/api/post/po/edit/' + this.po.hId, new FormData($('#poForm')[0])).then(response => {
                                this.backToList();
                            }).catch(e => { this.handleErrors(e); });
                        } else { }
                        Codebase.blocks('#poCRUDBlock', 'state_toggle');
                    });
                },
                getAllPO: function() {
                    Codebase.blocks('#poListBlock', 'state_toggle');
                    axios.get(route('api.get.po.read').url()).then(response => {
                        this.poList = response.data;
                        Codebase.blocks('#poListBlock', 'state_toggle');
                    }).catch(e => { this.handleErrors(e); });
                },
                onChangeSupplierType: function(type) {
                    if (type == 'SUPPLIERTYPE.WI') {
                        this.po.supplierHId = '';
                    }
                },
                createNew: function() {
                    this.mode = 'create';
                    this.errors.clear();
                    this.po = this.emptyPO();
                },
                editSelected: function(idx) {
                    this.mode = 'edit';
                    this.errors.clear();
                    this.po = this.poList[idx];
                },
                showSelected: function(idx) {
                    this.mode = 'show';
                    this.errors.clear();
                    this.po = this.poList[idx];
                },
                deleteSelected: function(idx) {
                    axios.post('/api/post/po/delete/' + idx).then(response => {
                        this.backToList();
                    }).catch(e => { this.handleErrors(e); });
                },
                backToList: function() {
                    this.mode = 'list';
                    this.errors.clear();
                    this.getAllPO();
                },
                emptyPO: function() {
                    return {
                        hId: '',
                        po_code: this.generatePOCode(),
                        po_created: '',
                        shipping_date: '',
                        supplier_type: '',
                        supplierHId: '',
                        warehouseHId: '',
                        vendorTruckingHId: '',
                        po_type: '',
                        po_status: 'POSTATUS.D',
                        productHId: '',
                        items: [],
                        expenses: [],
                        disc_total_percent : 0,
                        disc_total_value : 0
                    }
                },
                getSupplier: function() {
                    axios.get(route('api.get.supplier.read').url() + this.generateQueryStrings([{'key':'all', 'value':'yes'}])).then(
                        response => { this.supplierDDL = response.data; }
                    );
                },
                getSupplierType: function() {
                    axios.get(route('api.get.lookup.bycategory', 'SUPPLIER_TYPE').url()).then(
                        response => { this.supplierTypeDDL = response.data; }
                    );
                },
                getPOType: function() {
                    axios.get(route('api.get.lookup.bycategory', 'PO_TYPE').url()).then(
                        response => { this.poTypeDDL = response.data; }
                    );
                },
                generatePOCode: function() {
                    axios.get(route('api.get.po.generate.po_code').url()).then(
                        response => { this.po.po_code = response.data; }
                    );
                },
            },
            watch: {
                'po.supplierHId': function() {
                    if (this.po.supplierHId == '') {
                        this.selectedSupplier = {};
                    } else {
                        this.selectedSupplier = _.find(this.supplierDDL, { hId: this.po.supplierHId });
                    }
                },
                'po.po_status': function() {
                    if (this.po.po_status != '') {
                        axios.get(route('api.get.lookup.description.byvalue', 'POSTATUS.D').url()).then(
                            response => { this.poStatusDesc = response.data; }
                        );
                    }
                },
                mode: function() {
                    switch (this.mode) {
                        case 'create':
                        case 'edit':
                        case 'show':
                            Codebase.blocks('#poListBlock', 'close')
                            Codebase.blocks('#poCRUDBlock', 'open')
                            break;
                        case 'list':
                        default:
                            Codebase.blocks('#poListBlock', 'open')
                            Codebase.blocks('#poCRUDBlock', 'close')
                            break;
                    }
                }
            },
            computed: {
                flatPickrConfig: function() {
                    return {
                        enableTime: true,
                        dateFormat: "Y-m-d H:i:S",
                    }
                },
                defaultPleaseSelect: function() {
                    return '';
                }
            }
        });
    </script>
    <script type="application/javascript" src="{{ mix('js/apps/po.js') }}"></script>
@endsection
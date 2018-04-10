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
                        <template v-if="supplierList.data">
                            <tr v-for="(s, sIdx) in supplierList.data">
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        </template>
                        <template v-else>
                            <tr>
                                <td class="text-center" colspan="6">@lang('labels.DATA_NOT_FOUND')</td>
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
        <div class="block block-shadow-on-hover block-mode-loading-refresh" id="supplierCRUDBlock">
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
                    <ul class="nav nav-tabs nav-tabs-alt" data-toggle="tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" href="#tabs_supplier">
                                @lang('supplier.index.tabs.supplier')
                                <template v-if="errors.any('tabs_supplier')">
                                    &nbsp;<span id="supplierDataTabError" class="red-asterisk"><i class="fa fa-close fa-fw"></i></span>
                                </template>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#tabs_pic">
                                @lang('supplier.index.tabs.pic')
                                <template v-if="errors.any('tabs_pic')">
                                    &nbsp;<span id="picDataTabError" class="red-asterisk"><i class="fa fa-close fa-fw"></i></span>
                                </template>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#tabs_bankaccount">
                                @lang('supplier.index.tabs.bank_account')
                                <template v-if="errors.any('tabs_bankAccounts')">
                                    &nbsp;<span id="bankAccountTabError" class="red-asterisk"><i class="fa fa-close fa-fw"></i></span>
                                </template>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#tabs_product">
                                @lang('supplier.index.tabs.product')
                                <template v-if="errors.any('tabs_product')">
                                    &nbsp;<span id="productTabError" class="red-asterisk"><i class="fa fa-close fa-fw"></i></span>
                                </template>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#tabs_settings">
                                @lang('supplier.index.tabs.settings')
                                <template v-if="errors.any('tabs_settings')">
                                    &nbsp;<span id="settingsTabError" class="red-asterisk"><i class="fa fa-close fa-fw"></i></span>
                                </template>
                            </a>
                        </li>
                    </ul>
                    <div class="block-content tab-content overflow-hidden">
                        <div class="tab-pane fade fade-up show active" id="tabs_supplier" role="tabpanel">
                            <div v-bind:class="{ 'form-group row':true, 'is_invalid':errors.has('tabs_supplier.name') }">
                                <label for="inputName" class="col-2 col-form-label">@lang('supplier.fields.name')</label>
                                <div class="col-md-10">
                                    <input id="inputName" name="name" type="text" class="form-control" placeholder="@lang('supplier.fields.name')"
                                           v-model="supplier.name"
                                           v-validate="'required'" data-vv-as="{{ trans('supplier.fields.name') }}" data-vv-scope="tabs_supplier">
                                    <span v-show="errors.has('tabs_supplier.name')" class="invalid-feedback" v-cloak>@{{ errors.first('tabs_supplier.name') }}</span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="inputCodeSign" class="col-2 col-form-label">@lang('supplier.fields.code_sign')</label>
                                <div class="col-md-10">
                                    <input id="inputCodeSign" name="code_sign" v-model="supplier.code_sign" type="text" class="form-control" placeholder="@lang('supplier.fields.code_sign')">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="inputAddress" class="col-2 col-form-label">@lang('supplier.fields.address')</label>
                                <div class="col-md-10">
                                    <textarea name="address" v-model="supplier.address" id="inputAddress" class="form-control" rows="4"></textarea>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="inputCity" class="col-2 col-form-label">@lang('supplier.fields.city')</label>
                                <div class="col-md-10">
                                    <input id="inputCity" name="city" v-model="supplier.city" type="text" class="form-control" placeholder="@lang('supplier.fields.city')">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="inputPhone" class="col-2 col-form-label">@lang('supplier.fields.phone')</label>
                                <div class="col-md-10">
                                    <input id="inputPhone" name="phone" type="text" v-model="supplier.phone" class="form-control" placeholder="@lang('supplier.fields.phone')">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="inputFax" class="col-2 col-form-label">@lang('supplier.fields.fax_num')</label>
                                <div class="col-md-10">
                                    <input id="inputFax" name="fax_num" type="text" v-model="supplier.fax_num" class="form-control" placeholder="@lang('supplier.fields.fax_num')">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="inputTaxId" class="col-2 col-form-label">@lang('supplier.fields.tax_id')</label>
                                <div class="col-md-10">
                                    <input id="inputTaxId" name="tax_id" type="text" v-model="supplier.tax_id" class="form-control" placeholder="@lang('supplier.fields.tax_id')">
                                </div>
                            </div>
                            <div v-bind:class="{ 'form-group row':true, 'is-invalid':errors.has('tabs_supplier.status') }">
                                <label for="inputStatus" class="col-2 col-form-label">@lang('supplier.fields.status')</label>
                                <div class="col-md-10">
                                    <select id="inputStatus"
                                            class="form-control"
                                            name="product.status"
                                            v-validate="'required'"
                                            data-vv-as="{{ trans('supplier.fields.status') }}"
                                            data-vv-scope="tabs_supplier">
                                        <option v-bind:value="defaultPleaseSelect">@lang('labels.PLEASE_SELECT')</option>
                                        <option v-for="(s, sIdx) in statusDDL" v-bind:value="s.hId">@{{ s.description }}</option>
                                    </select>
                                    <span v-show="errors.has('tabs_supplier.status')" class="invalid-feedback" v-cloak>@{{ errors.first('tabs_supplier.status') }}</span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="inputRemarks" class="col-2 col-form-label">@lang('supplier.fields.remarks')</label>
                                <div class="col-md-10">
                                    <input id="inputRemarks" name="remarks" type="text" class="form-control" placeholder="@lang('supplier.fields.remarks')">
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade fade-up show" id="tabs_pic" role="tabpanel">

                        </div>
                        <div class="tab-pane fade fade-up show" id="tabs_bankaccount" role="tabpanel">
                            <table class="table table-bordered">
                                <thead class="thead-light">
                                    <tr>
                                        <th class="text-center">@lang('supplier.index.table.table_bank.header.bank')</th>
                                        <th class="text-center">@lang('supplier.index.table.table_bank.header.account_name')</th>
                                        <th class="text-center">@lang('supplier.index.table.table_bank.header.account_number')</th>
                                        <th class="text-center">@lang('supplier.index.table.table_bank.header.remarks')</th>
                                        <th class="text-center">@lang('labels.ACTION')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(ba, baIdx) in supplier.bank_accounts">
                                        <td v-bind:class="{ 'is-invalid':errors.has('tabs_bank.bank_' + baIdx) }">
                                            <select class="form-control"
                                                    name="bank[]"
                                                    v-model="ba.bankHId"
                                                    v-validate="'required'"
                                                    v-bind:data-vv-as="'{{ trans('supplier.create.table_bank.header.bank') }} ' + (bankIdx + 1)"
                                                    v-bind:data-vv-name="'bank_' + bankIdx"
                                                    data-vv-scope="tabs_bankaccounts">
                                                <option v-bind:value="defaultPleaseSelect">@lang('labels.PLEASE_SELECT')</option>
                                                <option v-for="(b, bIdx) in bankDDL" v-bind:value="b.hId">@{{ b.bankFullName }}</option>
                                            </select>
                                        </td>
                                        <td v-bind:class="{ 'has-error':errors.has('tab_bank.account_name_' + bankIdx) }">
                                            <input type="text" class="form-control" name="account_name[]" v-model="bank.account_name"
                                                   v-validate="'required'"
                                                   v-bind:data-vv-as="'{{ trans('supplier.create.table_bank.header.account_name') }} ' + (bankIdx + 1)"
                                                   v-bind:data-vv-name="'account_name_' + bankIdx"
                                                   data-vv-scope="tab_bank">
                                        </td>
                                        <td v-bind:class="{ 'has-error':errors.has('tab_bank.account_number_' + bankIdx) }">
                                            <input type="text" class="form-control" name="account_number[]" v-model="bank.account_number"
                                                   v-validate="'required|numeric'" v-bind:data-vv-as="'{{ trans('supplier.create.table_bank.header.account_number') }} ' + (bankIdx + 1)"
                                                   v-bind:data-vv-name="'account_number_' + bankIdx"
                                                   data-vv-scope="tab_bank">
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" name="bank_remarks[]" v-model="bank.remarks">
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-xs btn-danger" v-bind:data="bankIdx" v-on:click="removeSelectedBank(bankIdx)"><span class="fa fa-close fa-fw"></span></button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <button class="btn btn-xs btn-default" type="button" v-on:click="addNewBankAccount">@lang('buttons.create_new_button')</button>
                        </div>
                        <div class="tab-pane fade fade-up show" id="tabs_product" role="tabpanel">
                            <table class="table table-bordered">
                                <thead class="thead-light">
                                    <tr>
                                        <th></th>
                                        <th class="text-center">@lang('supplier.index.table.table_prod.header.type')</th>
                                        <th class="text-center">@lang('supplier.index.table.table_prod.header.name')</th>
                                        <th class="text-center">@lang('supplier.index.table.table_prod.header.short_code')</th>
                                        <th class="text-center">@lang('supplier.index.table.table_prod.header.description')</th>
                                        <th class="text-center">@lang('supplier.index.table.table_prod.header.remarks')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="p in supplier.products">
                                        <td class="text-center">
                                            <input type="checkbox" name="productSelected[]" v-bind:value="p.hId">
                                        </td>
                                        <td>@{{ p.productType.name }}</td>
                                        <td>@{{ p.name }}</td>
                                        <td>@{{ p.short_code }}</td>
                                        <td>@{{ p.description }}</td>
                                        <td>@{{ p.remarks }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane fade fade-up show" id="tabs_settings" role="tabpanel">
                            <div v-bind:class="{ 'form-group row':true, 'is-invalid':errors.has('tabs_settings.payment_due_day') }">
                                <label for="inputPaymentDueDay" class="col-2 col-form-label">@lang('supplier.fields.payment_due_day')</label>
                                <div class="col-md-10">
                                    <input id="inputPaymentDueDay" name="supplier.payment_due_day" type="text" class="form-control"
                                           v-validate="'required|numeric|max_value:100'" data-vv-as="{{ trans('supplier.fields.payment_due_day') }}" data-vv-scope="tabs_settings">
                                    <span v-show="errors.has('payment_due_day')" class="invalid-feedback" v-cloak>@{{ errors.first('payment_due_day') }}</span>
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
                bankDDL: [],
                providerDDL: [],
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
                        name: '',
                        code_sign: '',
                        address: '',
                        city: '',
                        phone_number: '',
                        fax_num: '',
                        tax_id: '',
                        status: '',
                        remarks: '',
                        payment_due_day: '',
                        bank_accounts: [],
                        persons_in_charge: [],
                        products: []
                    }
                },
                addNewBankAccount: function() {
                    this.supplier.bank_accounts.push({
                        bankHId: '',
                        account_name: '',
                        account_number: '',
                        remarks: ''
                    });
                },
                removeSelectedBank: function(idx) {
                    this.supplier.bank_accounts.splice(idx, 1);
                },
                addNewProfile: function() {
                    this.supplier.persons_in_charge.push({
                        'first_name': '',
                        'last_name': '',
                        'address': '',
                        'ic_num': '',
                        'image_filename': '',
                        'phone_numbers':[{
                            'phoneProviderHId': '',
                            'number': '',
                            'remarks': ''
                        }]
                    });
                },
                removeSelectedProfile: function(idx) {
                    this.supplier.persons_in_charge.splice(idx, 1);
                }
            },
            watch: {
                mode: function() {
                    switch (this.mode) {
                        case 'create':
                        case 'edit':
                        case 'show':
                            Codebase.blocks('#supplierListBlock', 'close')
                            Codebase.blocks('#supplierCRUDBlock', 'open')
                            break;
                        case 'list':
                        default:
                            Codebase.blocks('#supplierListBlock', 'open')
                            Codebase.blocks('#supplierCRUDBlock', 'close')
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
    <script type="application/javascript" src="{{ mix('js/apps/supplier.js') }}"></script>
@endsection
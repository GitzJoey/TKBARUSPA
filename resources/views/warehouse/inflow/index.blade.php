@extends('layouts.codebase.master')

@section('title')
    @lang('warehouse_inflow.index.title')
@endsection

@section('page_title')
    <span class="fa fa-mail-forward fa-rotate-90 fa-fw"></span>
    @lang('warehouse_inflow.index.page_title')
@endsection

@section('page_title_desc')
    @lang('warehouse_inflow.index.page_title_desc')
@endsection

@section('breadcrumbs')

@endsection

@section('content')
    <div id="inflowVue">
        @include ('layouts.common.error')
        <div class="block block-shadow-on-hover block-mode-loading-refresh" id="inflowListBlock">
            <div class="block-header block-header-default">
                <h3 class="block-title">@lang('warehouse_inflow.index.panel.list_panel.title')</h3>
                <div class="block-options">
                    <button type="button" class="btn-block-option" data-toggle="block-option" data-action="fullscreen_toggle"></button>
                    <button type="button" class="btn-block-option" v-on:click="renderInflowData">
                        <i class="si si-refresh"></i>
                    </button>
                    <button type="button" class="btn-block-option" data-toggle="block-option" data-action="content_toggle"></button>
                </div>
            </div>
            <div class="block-content">
                <select id="inputWarehouse"
                        class="form-control"
                        v-model="selectedWarehouse"
                        v-on:change="renderInflowData(selectedWarehouse)">
                    <option v-bind:value="defaultPleaseSelect">@lang('labels.PLEASE_SELECT')</option>
                    <option v-for="warehouse in warehouseDDL" v-bind:value="warehouse.hId">@{{ warehouse.name }} @{{ warehouse.address != '' ? '- ' + warehouse.address:''}}</option>
                </select>
                <br/>
                <div class="">
                    <table class="table table-bordered table-striped table-vcenter">
                        <thead class="thead-light">
                            <tr>
                                <th>@lang('warehouse_inflow.index.table.po_list.header.code')</th>
                                <th>@lang('warehouse_inflow.index.table.po_list.header.supplier')</th>
                                <th>@lang('warehouse_inflow.index.table.po_list.header.shipping_date')</th>
                                <th>@lang('warehouse_inflow.index.table.po_list.header.receipt')</th>
                                <th class="text-center" width="10%">@lang('labels.ACTION')</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-if="poWAList.length == 0">
                                <td colspan="5" class="text-center">@lang('labels.DATA_NOT_FOUND')</td>
                            </tr>
                            <tr v-for="(p, pIdx) in poWAList">
                                <td>@{{ p.code }}</td>
                                <td>@{{ p.supplier_type == 'SUPPLIERTYPE.WI' ? p.walk_in_supplier : p.supplier.name }}</td>
                                <td>@{{ p.shipping_date }}</td>
                                <td>@{{ p.receipts == undefined? 0:p.receipts.length }}</td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <button class="btn btn-sm btn-secondary" v-on:click="createNew(pIdx)">
                                            <span class="fa fa-plus fa-fw"></span>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-secondary dropdown-toggle" id="btnEdit" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-bind:disabled="p.receipts == 0 ? true:false"><span class="fa fa-pencil fa-fw"></span></button>
                                        <div class="dropdown-menu" aria-labelledby="btnEdit">
                                            <a class="dropdown-item" href="#" v-for="(r, rIdx) in p.receipts">
                                                @lang('warehouse_inflow.fields.receipt_no') @{{ rIdx + 1}}
                                            </a>
                                        </div>
                                        <button type="button" class="btn btn-sm btn-secondary dropdown-toggle" id="btnDelete" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-bind:disabled="p.receipts == 0 ? true:false"><span class="fa fa-close fa-fw"></span></button>
                                        <div class="dropdown-menu" aria-labelledby="btnDelete">
                                            <a class="dropdown-item" href="#" v-for="(r, rIdx) in p.receipts">
                                                @lang('warehouse_inflow.fields.receipt_no') @{{ rIdx + 1}}
                                            </a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="row items-push-2x text-center text-sm-left">
                    <div class="col-sm-6 col-xl-4">
                        <button type="button" class="btn btn-primary btn-lg btn-circle" data-toggle="tooltip" data-placement="top" title="{{ Lang::get('buttons.print_preview_button') }}">
                            <i class="fa fa-print fa-fw"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="block block-shadow-on-hover block-mode-loading-refresh" id="inflowCRUDBlock">
            <div class="block-header block-header-default">
                <h3 class="block-title">
                    <template v-if="mode == 'create'">@lang('warehouse_inflow.index.panel.crud_panel.title_create')</template>
                    <template v-if="mode == 'show'">@lang('warehouse_inflow.index.panel.crud_panel.title_show')</template>
                    <template v-if="mode == 'edit'">@lang('warehouse_inflow.index.panel.crud_panel.title_edit')</template>
                </h3>
                <div class="block-options">
                    <button type="button" class="btn-block-option" data-toggle="block-option" data-action="fullscreen_toggle"></button>
                    <button type="button" class="btn-block-option" data-toggle="block-option" data-action="content_toggle"></button>
                </div>
            </div>
            <div class="block-content">
                <form id="inflowForm" method="post" v-on:submit.prevent="validateBeforeSubmit">
                    <div class="form-group row">
                        <label for="inputPODetail" class="col-3 col-form-label">@lang('warehouse_inflow.fields.po_detail')</label>
                        <div class="col-md-9">
                            <div class="form-control-plaintext">@{{ po.code }}</div>
                            <input type="hidden" name="po_id" v-model="po.hId">
                        </div>
                    </div>
                    <div class="form-group row" v-show="po.receipts.length != 0">
                        <label for="inputPOReceiptDetail" class="col-3 col-form-label">&nbsp;</label>
                        <div class="col-md-9">
                            <template v-for="(r, rIdx) in po.receipts">
                                <table class="table table-bordered">
                                    <thead class="thead-light">
                                        <tr>
                                            <th colspan="4">@lang('warehouse_inflow.index.table.receipt_details_table.header.receipt_date')&nbsp;&nbsp;&nbsp;@{{ r.receipt_date }}</th>
                                        </tr>
                                        <tr>
                                            <th class="text-center">@lang('warehouse_inflow.index.table.receipt_details_table.header.product')</th>
                                            <th class="text-center">@lang('warehouse_inflow.index.table.receipt_details_table.header.brutto')</th>
                                            <th class="text-center">@lang('warehouse_inflow.index.table.receipt_details_table.header.netto')</th>
                                            <th class="text-center">@lang('warehouse_inflow.index.table.receipt_details_table.header.tare')</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(rd, rdIdx) in r.receipt_details">
                                            <td>
                                                @{{ rd.item.product.name }}
                                            </td>
                                            <td class="text-right">
                                                @{{ rd.brutto }} @{{ rd.selectedUnit }}
                                            </td>
                                            <td class="text-right">
                                                @{{ rd.netto }} @{{ rd.selectedUnit }}
                                            </td>
                                            <td class="text-right">
                                                @{{ rd.tare }} @{{ rd.selectedUnit }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </template>
                        </div>
                    </div>
                    <hr/>
                    <div v-bind:class="{ 'form-group row':true, 'is-invalid':errors.has('receipt_date') }">
                        <label for="inputReceiptDate" class="col-3 col-form-label">@lang('warehouse_inflow.fields.receipt_date')</label>
                        <div class="col-md-9">
                            <flat-pickr id="inputReceiptDate" class="form-control" name="receipt_date"
                                        v-model="receipt.receipt_date" v-bind:config="defaultFlatPickrConfig"
                                        v-validate="'required'" data-vv-as="{{ trans('warehouse_inflow.fields.receipt_date') }}"
                                        data-vv-name="{{ trans('warehouse_inflow.fields.receipt_date') }}"></flat-pickr>
                            <span v-show="errors.has('receipt_date')" class="invalid-feedback">@{{ errors.first('receipt_date') }}</span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputVendorTrucking" class="col-3 col-form-label">@lang('warehouse_inflow.fields.vendor_trucking')</label>
                        <div class="col-md-9">
                            <template v-if="mode == 'create' || mode == 'edit'">
                                <select id="inputVendorTrucking" name="vendor_trucking_id" class="form-control"
                                        v-model="receipt.vendorTruckingHId" v-on:change="onChangeVendorTrucking">
                                    <option v-bind:value="defaultPleaseSelect">@lang('labels.PLEASE_SELECT')</option>
                                    <option v-for="(vendorTrucking, vendorTruckingIdx) of vendorTruckingDDL" v-bind:value="vendorTrucking.hId">@{{ vendorTrucking.name }}</option>
                                </select>
                            </template>
                        </div>
                    </div>
                    <div v-bind:class="{ 'form-group row':true, 'is-invalid':errors.has('license_plate') }">
                        <label for="inputLicensePlate" class="col-3 col-form-label">@lang('warehouse_inflow.fields.license_plate')</label>
                        <div class="col-md-9">
                            <select id="selectLicensePlate" class="form-control" name="truck_id"
                                    v-model="receipt.truckHId">
                                <option v-bind:value="defaultPleaseSelect">@lang('labels.PLEASE_SELECT')</option>
                                <option v-for="(truck, truckIdx) of truckDDL" v-bind:value="truck.hId">@{{ truck.license_plate }}</option>
                            </select>
                        </div>
                    </div>
                    <div v-bind:class="{ 'form-group row':true, 'is-invalid':errors.has('driver_name') }">
                        <label for="inputDriverName" class="col-3 col-form-label">@lang('warehouse_inflow.fields.driver_name')</label>
                        <div class="col-md-9">
                            <input id="inputDriverName" name="driver_name" v-model="receipt.driver_name" type="text" class="form-control" placeholder="{{ trans('warehouse_inflow.fields.driver_name') }}">
                        </div>
                        <span v-show="errors.has('driver_name')" class="invalid-feedback">@{{ errors.first('driver_name') }}</span>
                    </div>
                    <br/>
                    <div class="row">
                        <div class="col-md-12">
                            <table id="receiptListTable" class="table table-bordered table-hover">
                                <thead class="thead-light">
                                    <tr>
                                        <th width="50%">@lang('warehouse_inflow.index.table.item_table.header.product_name')</th>
                                        <th width="15%" class="text-center">@lang('warehouse_inflow.index.table.item_table.header.unit')</th>
                                        <th width="10%" class="text-center">@lang('warehouse_inflow.index.table.item_table.header.brutto')</th>
                                        <th width="10%" class="text-center">@lang('warehouse_inflow.index.table.item_table.header.netto')</th>
                                        <th width="10%" class="text-center">@lang('warehouse_inflow.index.table.item_table.header.tare')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(rd, rdIdx) in receipt.receipt_details">
                                        <td>
                                            @{{ rd.item.product.name }}
                                            <input type="hidden" name="receipt_detail_id" v-model="rd.hId">
                                            <input type="hidden" name="item_id[]" v-model="rd.item.hId">
                                            <input type="hidden" name="product_id[]" v-model="rd.item.product.hId">
                                            <input type="hidden" name="base_product_unit_id[]" v-model="rd.item.base_product_unit.hId">
                                        </td>
                                        <td v-bind:class="{ 'is-invalid':errors.has('punit_' + rdIdx) }">
                                            <select name="selected_product_unit_id[]"
                                                    class="form-control"
                                                    v-model="rd.selectedProductUnitsHId"
                                                    v-validate="'required|checkequal:' + rdIdx"
                                                    v-bind:data-vv-as="'{{ trans('warehouse_inflow.index.table.item_table.header.unit') }} ' + (rdIdx + 1)"
                                                    v-bind:data-vv-name="'punit_' + rdIdx"
                                                    v-on:change="onChangeSelectedProductUnit(rdIdx)">
                                                <option value="">@lang('labels.PLEASE_SELECT')</option>
                                                <option v-for="product_unit in rd.item.product.product_units" v-bind:value="product_unit.hId">@{{ product_unit.unit.name }} (@{{ product_unit.unit.symbol }})</option>
                                            </select>
                                            <input type="hidden" name="conversion_value[]" v-model="rd.selected_product_units.conversion_value">
                                        </td>
                                        <td v-bind:class="{ 'is-invalid':errors.has('brutto_' + rdIdx) }">
                                            <vue-autonumeric v-bind:id="'brutto_' + rdIdx" class="form-control text-right"
                                                    v-model="rd.brutto" v-validate="'required|checkequal:' + rdIdx"
                                                    v-bind:data-vv-as="'{{ trans('warehouse_inflow.index.table.item_table.header.brutto') }} ' + (rdIdx + 1)"
                                                    v-bind:data-vv-name="'brutto_' + rdIdx"
                                                    v-bind:options="defaultNumericConfig"
                                                    v-on:input="reValidate('brutto', rdIdx)"></vue-autonumeric>
                                            <input type="hidden" name="brutto[]" v-model="rd.brutto">
                                        </td>
                                        <td v-bind:class="{ 'is-invalid':errors.has('netto_' + rdIdx) }">
                                            <vue-autonumeric v-bind:id="'netto_' + rdIdx" class="form-control text-right"
                                                    v-model="rd.netto" v-validate="'required|checkequal:' + rdIdx"
                                                    v-bind:data-vv-as="'{{ trans('warehouse_inflow.index.table.item_table.header.netto') }} ' + (rdIdx + 1)"
                                                    v-bind:data-vv-name="'netto_' + rdIdx"
                                                    v-bind:options="defaultNumericConfig"
                                                    v-on:input="reValidate('netto', rdIdx)"></vue-autonumeric>
                                            <input type="hidden" name="netto[]" v-model="rd.netto">
                                        </td>
                                        <td v-bind:class="{ 'is-invalid':errors.has('tare_' + rdIdx) }">
                                            <vue-autonumeric v-bind:id="'tare_' + rdIdx" class="form-control text-right"
                                                    v-model="rd.tare" v-validate="'required|checkequal:' + rdIdx"
                                                    v-bind:data-vv-as="'{{ trans('warehouse_inflow.index.table.item_table.header.tare') }} ' + (rdIdx + 1)"
                                                    v-bind:data-vv-name="'tare_' + rdIdx"
                                                    v-bind:options="defaultNumericConfig"
                                                    v-on:input="reValidate('tare', rdIdx)"></vue-autonumeric>
                                            <input type="hidden" name="tare[]" v-model="rd.tare">
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <br/>
                    <div class="table-responsive">
                        <table id="expensesListTable" class="table table-bordered table-striped table-vcenter">
                            <thead class="thead-light">
                                <tr>
                                    <th colspan="5">@lang('warehouse_inflow.index.table.expense_table.header.title')</th>
                                    <th class="text-align-right">
                                        <template v-if="mode == 'create' || mode == 'edit'">
                                            <button type="button" class="btn-block-option"
                                                    data-toggle="tooltip" title="{{ trans('buttons.create_new_button') }}"
                                                    v-on:click="addExpense">
                                                <i class="si si-plus"></i>
                                            </button>
                                        </template>
                                        <template v-if="mode == 'show'">
                                        </template>
                                    </th>
                                </tr>
                                <tr>
                                    <th width="20%">@lang('warehouse_inflow.index.table.expense_table.header.name')</th>
                                    <th width="20%" class="text-center">@lang('warehouse_inflow.index.table.expense_table.header.type')</th>
                                    <th width="10%" class="text-center">@lang('warehouse_inflow.index.table.expense_table.header.internal_expense')</th>
                                    <th width="25%" class="text-center">@lang('warehouse_inflow.index.table.expense_table.header.remarks')</th>
                                    <th width="5%">&nbsp;</th>
                                    <th width="20%" class="text-center">@lang('warehouse_inflow.index.table.expense_table.header.amount')</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-if="expenses.length == 0">
                                    <td colspan="6" class="text-center">@lang('labels.DATA_NOT_FOUND')</td>
                                </tr>
                                <tr v-for="(expense, expenseIndex) in expenses">
                                    <td v-bind:class="{ 'is-invalid':errors.has('expense_name_' + expenseIndex) }">
                                        <template v-if="mode == 'create' || mode == 'edit'">
                                            <input name="expense_name[]" type="text" class="form-control"
                                                   v-model="expense.name" v-validate="'required'" v-bind:data-vv-as="'{{ trans('warehouse_inflow.index.table.expense_table.header.name') }} ' + (expenseIndex + 1)"
                                                   v-bind:data-vv-name="'expense_name_' + expenseIndex">
                                        </template>
                                        <input type="hidden" name="expense_id[]" v-model="expense.hId" />
                                    </td>
                                    <td v-bind:class="{ 'is-invalid':errors.has('expense_type_' + expenseIndex) }">
                                        <template v-if="mode == 'create' || mode == 'edit'">
                                            <select class="form-control" v-model="expense.type"
                                                    v-validate="'required'" v-bind:data-vv-as="'{{ trans('warehouse_inflow.index.table.expense_table.header.type') }} ' + (expenseIndex + 1)"
                                                    v-bind:data-vv-name="'expense_type_' + expenseIndex" disabled>
                                                <option v-bind:value="defaultPleaseSelect">@lang('labels.PLEASE_SELECT')</option>
                                                <option v-for="(expenseType, expenseTypeIdx) in expenseTypeDDL" v-bind:value="expenseType.code">@{{ expenseType.description }}</option>
                                            </select>
                                        </template>
                                        <input type="hidden" name="expense_type[]" v-model="expense.type">
                                    </td>
                                    <td class="text-center">
                                        <template v-if="mode == 'create' || mode == 'edit'">
                                            <input type="checkbox" v-model="expense.is_internal_expense" disabled>
                                        </template>
                                        <input type="hidden" name="is_internal_expense" v-model="expense.is_internal_expense_val">
                                    </td>
                                    <td>
                                        <template v-if="mode == 'create' || mode == 'edit'">
                                            <input name="expense_remarks[]" type="text" class="form-control" v-model="expense.remarks"/>
                                        </template>
                                    </td>
                                    <td class="text-center">
                                        <template v-if="mode == 'create' || mode == 'edit'">
                                            <button type="button" class="btn btn-danger btn-md" v-on:click="removeExpense(expenseIndex)">
                                                <span class="fa fa-minus"></span>
                                            </button>
                                        </template>
                                    </td>
                                    <td v-bind:class="{ 'is-invalid':errors.has('expense_amount_' + expenseIndex) }">
                                        <template v-if="mode == 'create' || mode == 'edit'">
                                            <vue-autonumeric type="text" class="form-control text-align-right"
                                                             v-model="expense.amount" v-validate="'required'"
                                                             v-bind:options="defaultCurrencyConfig"
                                                             v-bind:data-vv-as="'{{ trans('warehouse_inflow.index.table.expense_table.header.amount') }} ' + (expenseIndex + 1)"
                                                             v-bind:data-vv-name="'expense_amount_' + expenseIndex"><</vue-autonumeric>
                                            <input type="hidden" name="expense_amount[]" v-model="expense.amount">
                                        </template>
                                    </td>
                                </tr>
                            </tbody>
                            <tbody>
                                <tr v-if="expenses.length != 0">
                                    <td colspan="5" class="text-right">@lang('warehouse_inflow.index.table.expense_table.header.total')</td>
                                    <td class="text-right"><vue-autonumeric v-bind:tag="'span'" v-bind:options="currencyFormatToString" v-model="totalExpense"></vue-autonumeric></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <br/>
                    <div class="form-group row">
                        <label for="inputRemarks" class="col-3 col-form-label">@lang('warehouse_inflow.fields.remarks')</label>
                        <div class="col-md-9">
                            <template v-if="mode == 'create' || mode == 'edit'">
                                <input type="text" class="form-control" id="inputRemarks" name="remarks" v-model="receipt.remarks" placeholder="@lang('warehouse_inflow.fields.remarks')">
                            </template>
                            <template v-if="mode == 'show'">
                                <div class="form-control-plaintext">@{{ receipt.remarks }}</div>
                            </template>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-3 col-form-label" for="inputButton">&nbsp;</label>
                        <div class="col-9">
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
    @routes('warehouse_inflow')
@endsection

@section('custom_js')
    <script type="application/javascript">
        var inflowVue = new Vue ({
            el: '#inflowVue',
            data: {
                mode: '',
                warehouseDDL: [],
                vendorTruckingDDL: [],
                truckDDL: [],
                expenseTypeDDL: [],
                selectedWarehouse: '',
                poWAList: [],
                po: {
                    receipts: []
                },
                receipt: {
                    hId: '',
                    receipt_date: new Date(),
                    vendorTruckingHId: '',
                    truckHId: '',
                    article_code: '',
                    driver_name: '',
                    receipt_details: [],
                    remarks: ''
                },
                expenses: [

                ]
            },
            mounted: function () {
                this.$validator.extend('checkequal', {
                    getMessage: (field, args) => {
                        return this.$validator.locale == 'id' ?
                            'Nilai bersih dan Tara tidak sama dengan Nilai Kotor':'Netto and Tare value not equal with Bruto';
                    },
                    validate: (value, args) => {
                        var result = false;
                        var itemIdx = args[0];

                        if (this.receipt.receipt_details[itemIdx] == undefined) return true;

                        if (this.receipt.receipt_details[itemIdx].brutto ==
                            this.receipt.receipt_details[itemIdx].netto + this.receipt.receipt_details[itemIdx].tare) {
                            result = true;
                        }

                        return result;
                    }
                });

                this.mode = 'list';
                this.renderInflowData();
            },
            methods: {
                validateBeforeSubmit: function() {
                    this.$validator.validateAll().then(isValid => {
                        if (!isValid) { return; }
                        this.errors.clear();
                        this.loadingPanel('#inflowCRUDBlock', 'TOGGLE');
                        if (this.mode == 'create') {
                            axios.post(route('api.post.warehouse.inflow.save', this.po.hId).url(), new FormData($('#inflowForm')[0])).then(response => {
                                this.backToList();
                                this.loadingPanel('#inflowCRUDBlock', 'TOGGLE');
                            }).catch(e => {
                                this.handleErrors(e);
                                this.loadingPanel('#outflowCRUDBlock', 'TOGGLE');
                            });
                        } else if (this.mode == 'edit') {
                            axios.post(route('api.post.warehouse.inflow.edit', this.po.hId).url(), new FormData($('#inflowForm')[0])).then(response => {
                            }).catch(e => {
                                this.handleErrors(e);
                                this.loadingPanel('#outflowCRUDBlock', 'TOGGLE');
                            });
                        } else { }
                    });
                },
                createNew: function(index) {
                    this.mode = 'create';
                    this.errors.clear();
                    this.po = this.poWAList[index];

                    this.receipt = {
                        hId: '',
                        receipt_date: new Date(),
                        vendorTruckingHId: '',
                        truckHId: '',
                        article_code: '',
                        driver_name: '',
                        receipt_details: [],
                        remarks: ''
                    };

                    this.expenses = [];
                    for (var i = 0; i < this.po.items.length; i++) {
                        this.receipt.receipt_details.push({
                            item: _.cloneDeep(this.po.items[i]),
                            selected_product_units: {
                                hId: ''
                            },
                            selectedProductUnitsHId: '',
                            base_product_unit: _.cloneDeep(_.find(this.po.items[i].product.product_units, { is_base: 1 })),
                            baseProductUnitHId: _.cloneDeep(_.find(this.po.items[i].product.product_units, { is_base: 1 })).hId,
                            brutto: 0,
                            netto: 0,
                            tare: 0
                        });
                    };
                },
                editSelected: function(idx) {
                    this.mode = 'edit';
                    this.errors.clear();
                },
                deleteSelected: function(idx) {
                },
                renderInflowData: function() {
                    this.loadingPanel('#inflowListBlock', 'TOGGLE');
                    Promise.all([
                        this.getWarehouse(),
                        this.getVendorTrucking(),
                        this.getExpenseType(),
                        this.getPOWAList(this.selectedWarehouse)
                    ]).then(() => {
                        this.loadingPanel('#inflowListBlock', 'TOGGLE');
                    });
                },
                getWarehouse: function() {
                    return new Promise((resolve, reject) => {
                        axios.get(route('api.get.warehouse.read').url()).then(response => {
                            this.warehouseDDL = response.data;
                            resolve(true);
                        }).catch(e => {
                            this.handleErrors(e);
                            reject(e.response.data.message);
                        });
                    });
                },
                getPOWAList: function(warehouseId) {
                    return new Promise((resolve, reject) => {
                        if (warehouseId == '') {
                            resolve(true);
                            return;
                        }

                        this.poWAList = [];
                        axios.get(route('api.get.po.status.waiting_arrival', warehouseId).url()).then(response => {
                            this.poWAList = response.data;
                            resolve(true);
                        }).catch(e => {
                            this.handleErrors(e);
                            reject(e.response.data.message);
                        });
                    });
                },
                backToList: function() {
                    this.mode = 'list';
                    this.errors.clear();
                    this.po = {
                        receipts: []
                    };
                    this.renderInflowData();
                },
                getExpenseType: function() {
                    axios.get(route('api.get.lookup.bycategory', 'EXPENSE_TYPE').url()).then(
                        response => { this.expenseTypeDDL = response.data; }
                    );
                },
                getVendorTrucking: function() {
                    return new Promise((resolve, reject) => {
                        axios.get(route('api.get.truck.vendor_trucking.read').url()).then(response => {
                            this.vendorTruckingDDL = response.data;
                            resolve(true);
                        }).catch(e => {
                            this.handleErrors(e);
                            reject(e.response.data.message);
                        });
                    });
                },
                reValidate: function(field, idx) {
                    if (field == 'brutto') {
                        this.$validator.validate('netto_' + idx);
                        this.$validator.validate('tare_' + idx);
                    } else if (field == 'netto') {
                        this.$validator.validate('brutto_' + idx);
                        this.$validator.validate('tare_' + idx);
                    } else {
                        this.$validator.validate('brutto_' + idx);
                        this.$validator.validate('netto_' + idx);
                    }
                },
                onChangeSelectedProductUnit: function(itemIndex) {
                    if (this.receipt.receipt_details[itemIndex].selectedProductUnitsHId != '') {
                        var pUnit = _.find(this.receipt.receipt_details[itemIndex].item.product.product_units, { hId: this.receipt.receipt_details[itemIndex].selectedProductUnitsHId });
                        this.receipt.receipt_details[itemIndex].selected_product_units = pUnit;
                    }
                },
                onChangeVendorTrucking: function() {
                    this.truckDDL = [];
                    this.receipt.truckHId = '';
                    if (this.receipt.vendorTruckingHId != '') {
                        this.truckDDL = _.find(this.vendorTruckingDDL, { hId: this.receipt.vendorTruckingHId }).trucks;
                    }
                },
                addExpense: function () {
                    this.expenses.push({
                        hId: '',
                        name: '',
                        type: 'EXPENSETYPE.ADD',
                        is_internal_expense: true,
                        is_internal_expense_val: 1,
                        amount: 0,
                        remarks: ''
                    });
                },
                removeExpense: function (index) {
                    this.expenses.splice(index, 1);
                }
            },
            watch: {
                mode: function() {
                    switch (this.mode) {
                        case 'create':
                        case 'edit':
                        case 'show':
                            this.contentPanel('#inflowListBlock', 'CLOSE')
                            this.contentPanel('#inflowCRUDBlock', 'OPEN')
                            break;
                        case 'list':
                        default:
                            this.contentPanel('#inflowListBlock', 'OPEN')
                            this.contentPanel('#inflowCRUDBlock', 'CLOSE')
                            break;
                    }
                }
            },
            computed: {
                totalExpense: {
                    get: function() {
                        var result = 0;

                        _.each(this.expenses, function(e) {
                            result += e.amount;
                        });

                        return result;
                    },
                    set: function(newValue) {
                        return newValue;
                    }
                },
                defaultPleaseSelect: function() {
                    return '';
                },
                currencyFormatToString: function() {
                    var conf = Object.assign({}, this.defaultCurrencyConfig);

                    conf.readOnly = true;
                    conf.noEventListeners = true;

                    return conf;
                }
            }
        });
    </script>
    <script type="application/javascript" src="{{ mix('js/apps/warehouse_inflow.js') }}"></script>
@endsection
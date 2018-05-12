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
                    <button type="button" class="btn-block-option" v-on:click="renderInflowtData">
                        <i class="si si-refresh"></i>
                    </button>
                    <button type="button" class="btn-block-option" data-toggle="block-option" data-action="content_toggle"></button>
                </div>
            </div>
            <div class="block-content">
                <select id="inputWarehouse"
                        class="form-control"
                        v-model="selectedWarehouse"
                        v-on:change="renderInflowtData(selectedWarehouse)">
                    <option v-bind:value="defaultPleaseSelect">@lang('labels.PLEASE_SELECT')</option>
                    <option v-for="warehouse in warehouseDDL" v-bind:value="warehouse.hId">@{{ warehouse.name }} @{{ warehouse.address != '' ? '- ' + warehouse.address:''}}</option>
                </select>
                <br/>
                <div class="table-responsive">
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
                                <td>0</td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <button class="btn btn-sm btn-secondary" v-on:click="createNew(pIdx)">
                                            <span class="fa fa-plus fa-fw"></span>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-secondary dropdown-toggle" id="btnEdit" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class="fa fa-pencil fa-fw"></span></button>
                                        <div class="dropdown-menu" aria-labelledby="btnEdit">
                                            <a class="dropdown-item" href="javascript:void(0)">
                                                Receipt 1
                                            </a>
                                            <a class="dropdown-item" href="javascript:void(0)">
                                                Receipt 2
                                            </a>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item" href="javascript:void(0)">
                                                Final Receipt
                                            </a>
                                        </div>
                                        <button type="button" class="btn btn-sm btn-secondary dropdown-toggle" id="btnDelete" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class="fa fa-close fa-fw"></span></button>
                                        <div class="dropdown-menu" aria-labelledby="btnDelete">
                                            <a class="dropdown-item" href="javascript:void(0)">
                                                Receipt 1
                                            </a>
                                            <a class="dropdown-item" href="javascript:void(0)">
                                                Receipt 2
                                            </a>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item" href="javascript:void(0)">
                                                Final Receipt
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
                    <div v-bind:class="{ 'form-group row':true, 'is-invalid':errors.has('receipt_date') }">
                        <label for="inputReceiptDate" class="col-3 col-form-label">@lang('warehouse_inflow.fields.receipt_date')</label>
                        <div class="col-md-8">
                            <flat-pickr id="inputReceiptDate" class="form-control"
                                        v-model="selectedReceiptDate" v-bind:config="defaultFlatPickrConfig"
                                        v-validate="'required'" data-vv-as="{{ trans('warehouse_inflow.fields.receipt_date') }}"
                                        data-vv-name="{{ trans('warehouse_inflow.fields.receipt_date') }}"
                                        v-on:input="onChangeReceiptDate"></flat-pickr>
                            <span v-show="errors.has('receipt_date')" class="invalid-feedback">@{{ errors.first('receipt_date') }}</span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputVendorTrucking" class="col-3 col-form-label">@lang('warehouse_inflow.fields.vendor_trucking')</label>
                        <div class="col-md-8">
                            <select id="inputVendorTrucking" name="vendor_trucking_id" class="form-control"
                                    v-model="po.vendorTruckingHId">
                                <option v-bind:value="defaultPleaseSelect">@lang('labels.PLEASE_SELECT')</option>
                                <option v-for="(vendorTrucking, vendorTruckingIdx) of vendorTruckingDDL" v-bind:value="vendorTrucking.hId">@{{ vendorTrucking.name }}</option>
                            </select>
                        </div>
                    </div>
                    <div v-bind:class="{ 'form-group row':true, 'is-invalid':errors.has('license_plate') }">
                        <label for="inputLicensePlate" class="col-3 col-form-label">@lang('warehouse_inflow.fields.license_plate')</label>
                        <div class="col-md-8">
                            <div v-show="!readOnlyLicensePlateSelect">
                                <select id="selectLicensePlate" class="form-control"
                                        v-model="selectedLicensePlate"
                                        v-on:change="onChangeSelectLicensePlate" v-bind:disabled="readOnlyLicensePlateSelect">
                                    <option v-bind:value="defaultPleaseSelect">@lang('labels.PLEASE_SELECT')</option>
                                    <option v-for="(truck, truckIdx) of truckDDL" v-bind:value="truck.plate_number">@{{ truck.plate_number }}</option>
                                    <option v-bind:value="defaultPleaseSelect">@lang('labels.SELECT_OTHER')</option>
                                </select>
                                <br>
                            </div>
                            <input id="inputLicensePlate" type="text" name="license_plate" class="form-control"
                                   v-model="licensePlate"
                                   v-validate="'required'"
                                   v-bind:readonly="readOnlyLicensePlateSelect ? true:selectedLicensePlate == '' ? false:true"
                                   v-show="selectedLicensePlate != '' ? false:true"
                                   data-vv-as="{{ trans('warehouse_inflow.fields.license_plate') }}">
                            <span v-show="errors.has('license_plate')" class="invalid-feedback">@{{ errors.first('license_plate') }}</span>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table id="itemsListTable" class="table table-bordered table-striped table-vcenter">
                            <thead class="thead-light">
                                <tr>
                                    <th width="50%" class="text-center">@lang('warehouse_inflow.index.table.item_table.header.product_name')</th>
                                    <th width="15%" class="text-center">@lang('warehouse_inflow.index.table.item_table.header.unit')</th>
                                    <th width="10%" class="text-center">@lang('warehouse_inflow.index.table.item_table.header.brutto')</th>
                                    <th width="10%" class="text-center">@lang('warehouse_inflow.index.table.item_table.header.netto')</th>
                                    <th width="10%" class="text-center">@lang('warehouse_inflow.index.table.item_table.header.tare')</th>
                                    <th width="5%">&nbsp;</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(receipt, receiptIdx) in po.receipts">
                                    <td>
                                        @{{ receipt.item.product.name }}
                                        <input type="hidden" name="receipt_id[]" v-bind:value="receipt.hId">
                                        <input type="hidden" name="item_id[]" v-bind:value="receipt.itemHId">
                                        <input type="hidden" name="product_id[]" v-bind:value="receipt.item.productHId">
                                        <input type="hidden" name="base_product_unit_id[]" v-bind:value="receipt.baseProductUnitHId">
                                        <input type="hidden" name="receipt_date[]" v-model="receipt.receipt_date"/>
                                        <input type="hidden" name="license_plate[]" v-model="receipt.license_plate"/>
                                    </td>
                                    <td v-bind:class="{ 'is-invalid':errors.has('unit_' + receiptIdx) }">
                                        <select name="selected_product_unit_id[]"
                                                class="form-control"
                                                v-model="receipt.selectedProductUnitsHId"
                                                v-validate="'required'"
                                                v-bind:disabled="readOnly"
                                                v-bind:data-vv-as="'{{ trans('warehouse_inflow.index.table.item_table.header.unit') }} ' + (receiptIdx + 1)"
                                                v-bind:data-vv-name="'unit_' + receiptIdx"
                                                v-on:change="onChangeProductUnit(receiptIdx)">
                                            <option v-bind:value="defaultPleaseSelect">@lang('labels.PLEASE_SELECT')</option>
                                            <option v-for="product_unit in receipt.item.product.product_units" v-bind:value="product_unit.hId">@{{ product_unit.unit.name }} (@{{ product_unit.unit.symbol }})</option>
                                        </select>
                                        <input type="hidden" name="conversion_value[]" v-model="receipt.selected_product_units.conversion_value">
                                        <input type="hidden" name="base_product_unit_id[]" v-model="receipt.base_product_unit.hId">
                                    </td>
                                    <td v-bind:class="{ 'is-invalid':errors.has('brutto_' + receiptIdx) }">
                                        <vue-autonumeric v-bind:id="'brutto_' + receipt.item.hId" type="text" class="form-control text-right" name="brutto[]"
                                                v-model="receipt.brutto"
                                                v-validate="readOnly ? '':'required|checkequal:' + receiptIdx"
                                                v-bind:readonly="readOnly"
                                                v-bind:data-vv-as="'{{ trans('warehouse_inflow.index.table.item_table.header.brutto') }} ' + (receiptIdx + 1)"
                                                v-bind:data-vv-name="'brutto_' + receiptIdx"
                                                v-bind:options="defaultNumericConfig"
                                                v-on:input="reValidate('brutto', receiptIdx)"></vue-autonumeric>
                                    </td>
                                    <td v-bind:class="{ 'is-invalid':errors.has('netto_' + receiptIdx) }">
                                        <vue-autonumeric v-bind:id="'netto_' + receipt.item.hId" type="text" class="form-control text-right" name="netto[]"
                                                v-model="receipt.netto"
                                                v-validate="readOnly ? '':'required|checkequal:' + receiptIdx"
                                                v-bind:readonly="readOnly"
                                                v-bind:data-vv-as="'{{ trans('warehouse_inflow.index.table.item_table.header.netto') }} ' + (receiptIdx + 1)"
                                                v-bind:data-vv-name="'netto_' + receiptIdx"
                                                v-bind:options="defaultNumericConfig"
                                                v-on:input="reValidate('netto', receiptIdx)"></vue-autonumeric>
                                    </td>
                                    <td v-bind:class="{ 'is-invalid':errors.has('tare_' + receiptIdx) }">
                                        <vue-autonumeric v-bind:id="'tare_' + receipt.item.hId" type="text" class="form-control text-right" name="tare[]"
                                                v-model="receipt.tare"
                                                v-validate="readOnly ? '':'required|checkequal:' + receiptIdx"
                                                v-bind:readonly="readOnly"
                                                v-bind:data-vv-as="'{{ trans('warehouse_inflow.index.table.item_table.header.tare') }} ' + (receiptIdx + 1)"
                                                v-bind:data-vv-name="'tare_' + receiptIdx"
                                                v-bind:options="defaultNumericConfig"
                                                v-on:input="reValidate('tare', receiptIdx)"></vue-autonumeric>
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-danger btn-md" v-on:click="removeReceipt(receiptIdx)" disabled><span class="fa fa-minus"/></button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
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
                                <tr v-if="po.expenses.length == 0">
                                    <td colspan="6" class="text-center">@lang('labels.DATA_NOT_FOUND')</td>
                                </tr>
                                <tr v-for="(expense, expenseIndex) in po.expenses">
                                    <td v-bind:class="{ 'is-invalid':errors.has('expense_name_' + expenseIndex) }">
                                        <template v-if="mode == 'create' || mode == 'edit'">
                                            <input name="expense_name[]" type="text" class="form-control"
                                                   v-model="expense.name" v-validate="'required'" v-bind:data-vv-as="'{{ trans('purchase_order.index.table.expense_table.header.name') }} ' + (expenseIndex + 1)"
                                                   v-bind:data-vv-name="'expense_name_' + expenseIndex">
                                        </template>
                                        <input type="hidden" name="expense_id[]" v-model="expense.hId" />
                                    </td>
                                    <td v-bind:class="{ 'is-invalid':errors.has('expense_type_' + expenseIndex) }">
                                        <template v-if="mode == 'create' || mode == 'edit'">
                                            <select class="form-control" v-model="expense.type" name="expense_type[]"
                                                    v-validate="'required'" v-bind:data-vv-as="'{{ trans('purchase_order.index.table.expense_table.header.type') }} ' + (expenseIndex + 1)"
                                                    v-bind:data-vv-name="'expense_type_' + expenseIndex" disabled>
                                                <option v-bind:value="defaultPleaseSelect">@lang('labels.PLEASE_SELECT')</option>
                                                <option v-for="(expenseType, expenseTypeIdx) in expenseTypeDDL" v-bind:value="expenseType.code">@{{ expenseType.description }}</option>
                                            </select>
                                        </template>
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
                                            <vue-autonumeric name="expense_amount[]" type="text" class="form-control text-align-right"
                                                             v-model="expense.amount" v-validate="'required'"
                                                             v-bind:options="defaultCurrencyConfig"
                                                             v-bind:data-vv-as="'{{ trans('purchase_order.index.table.expense_table.header.amount') }} ' + (expenseIndex + 1)"
                                                             v-bind:data-vv-name="'expense_amount_' + expenseIndex"><</vue-autonumeric>
                                        </template>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="form-group row">
                        <div class="col-3 col-form-label">&nbsp;</div>
                        <div class="col-md-8">
                            <template v-if="mode == 'create' || mode == 'edit'">
                                <button type="submit" class="btn btn-primary min-width-125">
                                    @lang('buttons.submit_button')
                                </button>
                                <button type="button" class="btn btn-primary min-width-125">
                                    @lang('buttons.print_preview_button')
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
                warehouseDDL: [],
                vendorTruckingDDL: [],
                expenseTypeDDL: [],
                truckDDL: [],
                poWAList: [],
                mode: '',
                selectedWarehouse: '',
                selectedLicensePlate: '',
                readOnlyLicensePlateSelect: false,
                readOnly: false,
                licensePlate: '',
                po: {
                    hId: '',
                    warehouseHId: '',
                    warehouse: {
                        hId: '',
                        name: ''
                    },
                    items: [],
                    expenses: [],
                    supplier: {
                        hId: '',
                        name: ''
                    },
                    receipts: []
                },
                selectedReceiptDate: new Date()
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

                        if (this.po == undefined) { result = true; }
                        if (this.po.receipts == undefined) { result = true; }

                        if (this.po.receipts[itemIdx].brutto ==
                            this.po.receipts[itemIdx].netto + this.po.receipts[itemIdx].tare) {
                            result = true;
                        }

                        return result;
                    }
                });

                this.mode = 'list';
                this.renderInflowtData();
            },
            methods: {
                validateBeforeSubmit: function() {
                    this.$validator.validateAll().then(isValid => {
                        if (!isValid) { return; }
                        this.errors.clear();
                        if (this.mode == 'create') {
                            axios.post(route('api.post.warehouse.inflow.save', this.po.hId).url(), new FormData($('#inflowForm')[0])).then(response => {
                            }).catch(e => {
                                this.handleErrors(e);
                            });
                        } else if (this.mode == 'edit') {
                            axios.post(route('api.post.warehouse.inflow.edit', this.po.hId).url(), new FormData($('#inflowForm')[0])).then(response => {
                            }).catch(e => {
                                this.handleErrors(e);
                            });
                        } else { }
                    });
                },
                createNew: function(index) {
                    this.mode = 'create';
                    this.errors.clear();
                    this.po = this.poWAList[index];

                    if (!this.po.hasOwnProperty('receipts')) {
                        this.po.receipts = [];
                    }

                    for (var i = 0; i < this.po.items.length; i++) {
                        this.po.receipts.push({
                            hId: '',
                            receipt_date: new Date(),
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
                renderInflowtData: function() {
                    this.loadingPanel('#inflowListBlock', 'TOGGLE');
                    Promise.all([
                        this.getWarehouse(),
                        this.getVendorTrucking(),
                        this.getTruck(),
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
                    this.renderPOWAData(this.selectedWarehouse);
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
                getTruck: function() {
                    return new Promise((resolve, reject) => {
                        axios.get(route('api.get.truck.read').url()).then(response => {
                            this.truckDDL = response.data;
                            resolve(true);
                        }).catch(e => {
                            this.handleErrors(e);
                            reject(e.response.data.message);
                        });
                    });
                },
                onChangeSelectLicensePlate: function() {
                    if (this.selectedLicensePlate != '') {
                        this.licensePlate = this.selectedLicensePlate;
                    } else {
                        this.licensePlate = '';
                    }
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
                onChangeProductUnit: function(itemIndex) {
                    if (this.po.receipts[itemIndex].selectedProductUnitsHId != '') {
                        var pUnit = _.find(this.po.receipts[itemIndex].item.product.product_units, { hId: this.po.receipts[itemIndex].selectedProductUnitsHId });
                        _.merge(this.po.receipts[itemIndex].selected_product_units, pUnit);
                    }
                },
                onChangeReceiptDate: function() {

                },
                addExpense: function () {
                    if (!this.po.hasOwnProperty('expenses')) {
                        this.po.expenses = [];
                    }

                    this.po.expenses.push({
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
                    this.po.expenses.splice(index, 1);
                }
            },
            watch: {
                selectedLicensePlate: function() {

                },
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
                defaultPleaseSelect: function() {
                    return '';
                },
                numericFormatToString: function() {
                    var conf = Object.assign({}, this.defaultNumericConfig);

                    conf.readOnly = true;
                    conf.noEventListeners = true;

                    return conf;
                }
            }
        });
    </script>
    <script type="application/javascript" src="{{ mix('js/apps/warehouse_inflow.js') }}"></script>
@endsection
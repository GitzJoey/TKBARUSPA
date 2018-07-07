@extends('layouts.codebase.master')

@section('title')
    @lang('warehouse_outflow.index.title')
@endsection

@section('page_title')
    <span class="fa fa-mail-reply fa-rotate-90 fa-fw"></span>
    @lang('warehouse_outflow.index.page_title')
@endsection

@section('page_title_desc')
    @lang('warehouse_outflow.index.page_title_desc')
@endsection

@section('breadcrumbs')

@endsection

@section('content')
    <div id="outflowVue">
        @include ('layouts.common.error')
        <div class="block block-shadow-on-hover block-mode-loading-refresh" id="outflowListBlock">
            <div class="block-header block-header-default">
                <h3 class="block-title">@lang('warehouse_outflow.index.panel.list_panel.title')</h3>
                <div class="block-options">
                    <button type="button" class="btn-block-option" data-toggle="block-option" data-action="fullscreen_toggle"></button>
                    <button type="button" class="btn-block-option" v-on:click="renderOutflowData">
                        <i class="si si-refresh"></i>
                    </button>
                    <button type="button" class="btn-block-option" data-toggle="block-option" data-action="content_toggle"></button>
                </div>
            </div>
            <div class="block-content">
                <select id="inputWarehouse"
                        class="form-control"
                        v-model="selectedWarehouse"
                        v-on:change="renderOutflowData(selectedWarehouse)">
                    <option v-bind:value="defaultPleaseSelect">@lang('labels.PLEASE_SELECT')</option>
                    <option v-for="warehouse in warehouseDDL" v-bind:value="warehouse.hId">@{{ warehouse.name }} @{{ warehouse.address != '' ? '- ' + warehouse.address:''}}</option>
                </select>
                <br/>
                <div class="">
                    <table class="table table-bordered table-striped table-vcenter">
                        <thead class="thead-light">
                            <tr>
                                <th>@lang('warehouse_outflow.index.table.so_list.header.code')</th>
                                <th>@lang('warehouse_outflow.index.table.so_list.header.customer')</th>
                                <th>@lang('warehouse_outflow.index.table.so_list.header.shipping_date')</th>
                                <th>@lang('warehouse_outflow.index.table.so_list.header.deliver')</th>
                                <th class="text-center" width="10%">@lang('labels.ACTION')</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-if="soWDList.length == 0">
                                <td colspan="5" class="text-center">@lang('labels.DATA_NOT_FOUND')</td>
                            </tr>
                            <tr v-for="(s, sIdx) in soWDList">
                                <td>@{{ s.code }}</td>
                                <td>@{{ s.customer_type == 'CUSTOMERTYPE.WI' ? s.walk_in_cust : s.customer.name }}</td>
                                <td>@{{ s.shipping_date }}</td>
                                <td>@{{ s.delivers == undefined? 0:s.delivers.length }}</td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <button class="btn btn-sm btn-secondary" v-on:click="createNew(sIdx)">
                                            <span class="fa fa-plus fa-fw"></span>
                                        </button>
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
        <div class="block block-shadow-on-hover block-mode-loading-refresh" id="outflowCRUDBlock">
            <div class="block-header block-header-default">
                <h3 class="block-title">
                    <template v-if="mode == 'create'">@lang('warehouse_outflow.index.panel.crud_panel.title_create')</template>
                    <template v-if="mode == 'show'">@lang('warehouse_outflow.index.panel.crud_panel.title_show')</template>
                    <template v-if="mode == 'edit'">@lang('warehouse_outflow.index.panel.crud_panel.title_edit')</template>
                </h3>
                <div class="block-options">
                    <button type="button" class="btn-block-option" data-toggle="block-option" data-action="fullscreen_toggle"></button>
                    <button type="button" class="btn-block-option" data-toggle="block-option" data-action="content_toggle"></button>
                </div>
            </div>
            <div class="block-content">
                <form id="outflowForm" method="post" v-on:submit.prevent="validateBeforeSubmit">
                    <div class="form-group row">
                        <label for="inputSODetail" class="col-3 col-form-label">@lang('warehouse_outflow.fields.so_detail')</label>
                        <div class="col-md-9">
                            <div class="form-control-plaintext">@{{ so.code }}</div>
                            <input type="hidden" name="so_id" v-model="so.hId">
                        </div>
                    </div>
                    <div class="form-group row" v-show="so.delivers.length != 0">
                        <label for="inputSODeliverDetail" class="col-3 col-form-label">&nbsp;</label>
                        <div class="col-md-9">
                            <template v-for="(s, sIdx) in so.delivers">
                                <table class="table table-bordered">
                                    <thead class="thead-light">
                                        <tr>
                                            <th colspan="4">@lang('warehouse_outflow.index.table.deliver_details_table.header.deliver_date')&nbsp;&nbsp;&nbsp;@{{ s.deliver_date }}</th>
                                        </tr>
                                        <tr>
                                            <th class="text-center">@lang('warehouse_outflow.index.table.deliver_details_table.header.product')</th>
                                            <th class="text-center">@lang('warehouse_outflow.index.table.deliver_details_table.header.brutto')</th>
                                            <th class="text-center">@lang('warehouse_outflow.index.table.deliver_details_table.header.netto')</th>
                                            <th class="text-center">@lang('warehouse_outflow.index.table.deliver_details_table.header.tare')</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(sd, sdIdx) in s.deliver_details">
                                            <td>
                                                @{{ sd.item.product.name }}
                                            </td>
                                            <td class="text-right">
                                                @{{ sd.brutto }} @{{ sd.selectedUnit }}
                                            </td>
                                            <td class="text-right">
                                                @{{ sd.netto }} @{{ sd.selectedUnit }}
                                            </td>
                                            <td class="text-right">
                                                @{{ sd.tare }} @{{ sd.selectedUnit }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </template>
                        </div>
                    </div>
                    <hr/>
                    <div v-bind:class="{ 'form-group row':true, 'is-invalid':errors.has('deliver_date') }">
                        <label for="inputDeliverDate" class="col-3 col-form-label">@lang('warehouse_outflow.fields.deliver_date')</label>
                        <div class="col-md-9">
                            <flat-pickr id="inputDeliverDate" class="form-control" name="deliver_date"
                                        v-model="deliver.deliver_date" v-bind:config="defaultFlatPickrConfig"
                                        v-validate="'required'" data-vv-as="{{ trans('warehouse_outflow.fields.deliver_date') }}"
                                        data-vv-name="{{ trans('warehouse_outflow.fields.deliver_date') }}"></flat-pickr>
                            <span v-show="errors.has('deliver_date')" class="invalid-feedback">@{{ errors.first('deliver_date') }}</span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputVendorTrucking" class="col-3 col-form-label">@lang('warehouse_outflow.fields.vendor_trucking')</label>
                        <div class="col-md-9">
                            <template v-if="mode == 'create' || mode == 'edit'">
                                <select id="inputVendorTrucking" name="vendor_trucking_id" class="form-control"
                                        v-model="deliver.vendorTruckingHId" v-on:change="onChangeVendorTrucking">
                                    <option v-bind:value="defaultPleaseSelect">@lang('labels.PLEASE_SELECT')</option>
                                    <option v-for="(vendorTrucking, vendorTruckingIdx) of vendorTruckingDDL" v-bind:value="vendorTrucking.hId">@{{ vendorTrucking.name }}</option>
                                </select>
                            </template>
                        </div>
                    </div>
                    <div v-bind:class="{ 'form-group row':true, 'is-invalid':errors.has('license_plate') }">
                        <label for="inputLicensePlate" class="col-3 col-form-label">@lang('warehouse_outflow.fields.license_plate')</label>
                        <div class="col-md-9">
                            <select id="selectLicensePlate" class="form-control" name="truck_id"
                                    v-model="deliver.truckHId">
                                <option v-bind:value="defaultPleaseSelect">@lang('labels.PLEASE_SELECT')</option>
                                <option v-for="(truck, truckIdx) of truckDDL" v-bind:value="truck.hId">@{{ truck.license_plate }}</option>
                            </select>
                        </div>
                    </div>
                    <div v-bind:class="{ 'form-group row':true, 'is-invalid':errors.has('driver_name') }">
                        <label for="inputDriverName" class="col-3 col-form-label">@lang('warehouse_outflow.fields.driver_name')</label>
                        <div class="col-md-9">
                            <input id="inputDriverName" name="driver_name" v-model="deliver.driver_name" type="text" class="form-control" placeholder="{{ trans('warehouse_outflow.fields.driver_name') }}">
                        </div>
                        <span v-show="errors.has('driver_name')" class="invalid-feedback">@{{ errors.first('driver_name') }}</span>
                    </div>
                    <br/>
                    <div class="row">
                        <div class="col-md-12">
                            <table id="deliverListTable" class="table table-bordered table-hover">
                                <thead class="thead-light">
                                    <tr>
                                        <th>@lang('warehouse_outflow.index.table.item_table.header.product_name')</th>
                                        <th width="15%" class="text-center">@lang('warehouse_outflow.index.table.item_table.header.unit')</th>
                                        <th width="15%" class="text-center">@lang('warehouse_outflow.index.table.item_table.header.brutto')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(dd, ddIdx) in deliver.deliver_details">
                                        <td>
                                            @{{ dd.item.product.name }}
                                            <input type="hidden" name="deliver_detail_id" v-model="dd.hId">
                                            <input type="hidden" name="item_id[]" v-model="dd.item.hId">
                                            <input type="hidden" name="product_id[]" v-model="dd.item.product.hId">
                                            <input type="hidden" name="base_product_unit_id[]" v-model="dd.item.base_product_unit.hId">
                                        </td>
                                        <td v-bind:class="{ 'is-invalid':errors.has('punit_' + ddIdx) }">
                                            <select name="selected_product_unit_id[]"
                                                    class="form-control"
                                                    v-model="dd.selectedProductUnitsHId"
                                                    v-validate="'required'"
                                                    v-bind:data-vv-as="'{{ trans('warehouse_outflow.index.table.item_table.header.unit') }} ' + (ddIdx + 1)"
                                                    v-bind:data-vv-name="'punit_' + ddIdx"
                                                    v-on:change="onChangeSelectedProductUnit(ddIdx)">
                                                <option value="">@lang('labels.PLEASE_SELECT')</option>
                                                <option v-for="product_unit in dd.item.product.product_units" v-bind:value="product_unit.hId">@{{ product_unit.unit.name }} (@{{ product_unit.unit.symbol }})</option>
                                            </select>
                                            <input type="hidden" name="conversion_value[]" v-model="dd.selected_product_units.conversion_value">
                                        </td>
                                        <td v-bind:class="{ 'is-invalid':errors.has('brutto_' + ddIdx) }">
                                            <vue-autonumeric v-bind:id="'brutto_' + ddIdx" class="form-control text-right"
                                                    v-model="dd.brutto" v-validate="'required'"
                                                    v-bind:data-vv-as="'{{ trans('warehouse_outflow.index.table.item_table.header.brutto') }} ' + (ddIdx + 1)"
                                                    v-bind:data-vv-name="'brutto_' + ddIdx"
                                                    v-bind:options="defaultNumericConfig"></vue-autonumeric>
                                            <input type="hidden" name="brutto[]" v-model="dd.brutto">
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
                                    <th colspan="5">@lang('warehouse_outflow.index.table.expense_table.header.title')</th>
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
                                    <th width="20%">@lang('warehouse_outflow.index.table.expense_table.header.name')</th>
                                    <th width="20%" class="text-center">@lang('warehouse_outflow.index.table.expense_table.header.type')</th>
                                    <th width="10%" class="text-center">@lang('warehouse_outflow.index.table.expense_table.header.internal_expense')</th>
                                    <th width="25%" class="text-center">@lang('warehouse_outflow.index.table.expense_table.header.remarks')</th>
                                    <th width="5%">&nbsp;</th>
                                    <th width="20%" class="text-center">@lang('warehouse_outflow.index.table.expense_table.header.amount')</th>
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
                                                   v-model="expense.name" v-validate="'required'" v-bind:data-vv-as="'{{ trans('warehouse_outflow.index.table.expense_table.header.name') }} ' + (expenseIndex + 1)"
                                                   v-bind:data-vv-name="'expense_name_' + expenseIndex">
                                        </template>
                                        <input type="hidden" name="expense_id[]" v-model="expense.hId" />
                                    </td>
                                    <td v-bind:class="{ 'is-invalid':errors.has('expense_type_' + expenseIndex) }">
                                        <template v-if="mode == 'create' || mode == 'edit'">
                                            <select class="form-control" v-model="expense.type"
                                                    v-validate="'required'" v-bind:data-vv-as="'{{ trans('warehouse_outflow.index.table.expense_table.header.type') }} ' + (expenseIndex + 1)"
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
                                                             v-bind:data-vv-as="'{{ trans('warehouse_outflow.index.table.expense_table.header.amount') }} ' + (expenseIndex + 1)"
                                                             v-bind:data-vv-name="'expense_amount_' + expenseIndex"><</vue-autonumeric>
                                            <input type="hidden" name="expense_amount[]" v-model="expense.amount">
                                        </template>
                                    </td>
                                </tr>
                            </tbody>
                            <tbody>
                                <tr v-if="expenses.length != 0">
                                    <td colspan="5" class="text-right">@lang('warehouse_outflow.index.table.expense_table.header.total')</td>
                                    <td class="text-right"><vue-autonumeric v-bind:tag="'span'" v-bind:options="currencyFormatToString" v-model="totalExpense"></vue-autonumeric></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <br/>
                    <div class="form-group row">
                        <label for="inputRemarks" class="col-3 col-form-label">@lang('warehouse_outflow.fields.remarks')</label>
                        <div class="col-md-9">
                            <template v-if="mode == 'create' || mode == 'edit'">
                                <input type="text" class="form-control" id="inputRemarks" name="remarks" v-model="deliver.remarks" placeholder="@lang('warehouse_outflow.fields.remarks')">
                            </template>
                            <template v-if="mode == 'show'">
                                <div class="form-control-plaintext">@{{ deliver.remarks }}</div>
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
    @routes('warehouse_outflow')
@endsection

@section('custom_js')
    <script type="application/javascript">
        var outflowVue = new Vue ({
            el: '#outflowVue',
            data: {
                mode: '',
                warehouseDDL: [],
                vendorTruckingDDL: [],
                truckDDL: [],
                expenseTypeDDL: [],
                selectedWarehouse: '',
                soWDList: [],
                so: {
                    delivers: []
                },
                deliver: {
                    hId: '',
                    deliver_date: new Date(),
                    vendorTruckingHId: '',
                    truckHId: '',
                    article_code: '',
                    driver_name: '',
                    deliver_details: [],
                    remarks: ''
                },
                expenses: [

                ]
            },
            mounted: function () {
                this.mode = 'list';
                this.renderOutflowData();
            },
            methods: {
                validateBeforeSubmit: function() {
                    this.$validator.validateAll().then(isValid => {
                        if (!isValid) { return; }
                        this.errors.clear();
                        this.loadingPanel('#outflowCRUDBlock', 'TOGGLE');
                        if (this.mode == 'create') {
                            axios.post(route('api.post.warehouse.outflow.save', this.so.hId).url(), new FormData($('#outflowForm')[0])).then(response => {
                                this.backToList();
                                this.loadingPanel('#outflowCRUDBlock', 'TOGGLE');
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
                    this.so = this.soWDList[index];

                    this.deliver = {
                        hId: '',
                        deliver_date: new Date(),
                        vendorTruckingHId: '',
                        truckHId: '',
                        article_code: '',
                        driver_name: '',
                        deliver_details: [],
                        remarks: ''
                    };

                    this.expenses = [];
                    for (var i = 0; i < this.so.items.length; i++) {
                        if (this.so.items[i].stock.warehouseHId != this.selectedWarehouse) continue;
                        this.deliver.deliver_details.push({
                            item: _.cloneDeep(this.so.items[i]),
                            selected_product_units: {
                                hId: ''
                            },
                            selectedProductUnitsHId: '',
                            base_product_unit: _.cloneDeep(_.find(this.so.items[i].product.product_units, { is_base: 1 })),
                            baseProductUnitHId: _.cloneDeep(_.find(this.so.items[i].product.product_units, { is_base: 1 })).hId,
                            brutto: 0,
                            netto: 0,
                            tare: 0
                        });
                    };
                },
                renderOutflowData: function() {
                    this.loadingPanel('#outflowListBlock', 'TOGGLE');
                    Promise.all([
                        this.getWarehouse(),
                        this.getVendorTrucking(),
                        this.getExpenseType(),
                        this.getSOWDList(this.selectedWarehouse)
                    ]).then(() => {
                        this.loadingPanel('#outflowListBlock', 'TOGGLE');
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
                getSOWDList: function(warehouseId) {
                    return new Promise((resolve, reject) => {
                        this.soWDList = [];

                        if (warehouseId == '') {
                            resolve(true);
                            return;
                        }

                        axios.get(route('api.get.so.status.waiting_delivery', warehouseId).url()).then(response => {
                            this.soWDList = response.data;
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
                    this.so = {
                        delivers: []
                    };
                    this.renderOutflowData();
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
                onChangeSelectedProductUnit: function(itemIndex) {
                    if (this.deliver.deliver_details[itemIndex].selectedProductUnitsHId != '') {
                        var pUnit = _.find(this.deliver.deliver_details[itemIndex].item.product.product_units, { hId: this.deliver.deliver_details[itemIndex].selectedProductUnitsHId });
                        this.deliver.deliver_details[itemIndex].selected_product_units = pUnit;
                    }
                },
                onChangeVendorTrucking: function() {
                    this.truckDDL = [];
                    this.deliver.truckHId = '';
                    if (this.deliver.vendorTruckingHId != '') {
                        this.truckDDL = _.find(this.vendorTruckingDDL, { hId: this.deliver.vendorTruckingHId }).trucks;
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
                            this.contentPanel('#outflowListBlock', 'CLOSE')
                            this.contentPanel('#outflowCRUDBlock', 'OPEN')
                            break;
                        case 'list':
                        default:
                            this.contentPanel('#outflowListBlock', 'OPEN')
                            this.contentPanel('#outflowCRUDBlock', 'CLOSE')
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
    <script type="application/javascript" src="{{ mix('js/apps/warehouse_outflow.js') }}"></script>
@endsection
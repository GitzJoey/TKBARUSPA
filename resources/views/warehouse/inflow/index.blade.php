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
                        <div class="col-md-9">
                            <flat-pickr id="inputReceiptDate" class="form-control"
                                        v-model="receipt.receipt_date" v-bind:config="defaultFlatPickrConfig"
                                        v-validate="'required'" data-vv-as="{{ trans('warehouse_inflow.fields.receipt_date') }}"
                                        data-vv-name="{{ trans('warehouse_inflow.fields.receipt_date') }}"
                                        v-on:input="onChangeReceiptDate"></flat-pickr>
                            <span v-show="errors.has('receipt_date')" class="invalid-feedback">@{{ errors.first('receipt_date') }}</span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputVendorTrucking" class="col-3 col-form-label">@lang('warehouse_inflow.fields.vendor_trucking')</label>
                        <div class="col-md-9">
                            <template v-if="mode == 'create' || mode == 'edit'">
                                <select id="inputVendorTrucking" name="vendor_trucking_id" class="form-control"
                                        v-model="receipt.vendorTruckingHId">
                                    <option v-bind:value="defaultPleaseSelect">@lang('labels.PLEASE_SELECT')</option>
                                    <option v-for="(vendorTrucking, vendorTruckingIdx) of vendorTruckingDDL" v-bind:value="vendorTrucking.hId">@{{ vendorTrucking.name }}</option>
                                </select>
                            </template>
                        </div>
                    </div>
                    <div v-bind:class="{ 'form-group row':true, 'is-invalid':errors.has('license_plate') }">
                        <label for="inputLicensePlate" class="col-3 col-form-label">@lang('warehouse_inflow.fields.license_plate')</label>
                        <div class="col-md-9">
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
                    <div v-bind:class="{ 'form-group row':true, 'is-invalid':errors.has('driver_name') }">
                        <label for="inputDriverName" class="col-3 col-form-label">@lang('warehouse_inflow.fields.driver_name')</label>
                        <div class="col-md-9">
                            <input id="inputDriverName" name="driver_name" v-model="receipt.driver_name" type="text" class="form-control" placeholder="{{ trans('warehouse_inflow.fields.driver_name') }}">
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
                expenseTypeDDL: [],
                truckDDL: [],
                selectedWarehouse: '',
                poWAList: [],
                po: { },
                receipt: {
                    hId: '',
                    receipt_date: new Date(),
                    vendorTruckingHId: '',
                    article_code: '',
                    driver_name: '',
                    receipt_details: []
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
                    this.po = Object.assign({ }, this.poWAList[index]);

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
                    this.renderInflowtData();
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
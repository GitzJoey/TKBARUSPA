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
                                        <button class="btn btn-sm btn-secondary" v-on:click="createNew(sIdx)"><span class="fa fa-check-square-o fa-fw"></span></button>
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
        <div class="block block-shadow-on-hover block-mode-loading-refresh" id="stockOpnameCRUDBlock">
            <div class="block-header block-header-default">
                <h3 class="block-title">
                    <template v-if="mode == 'create'">@lang('stock_opname.index.panel.crud_panel.title_create')</template>
                </h3>
                <div class="block-options">
                    <button type="button" class="btn-block-option" data-toggle="block-option" data-action="fullscreen_toggle"></button>
                    <button type="button" class="btn-block-option" data-toggle="block-option" data-action="content_toggle"></button>
                </div>
            </div>
            <div class="block-content">
                <form id="stockOpnameForm" method="post" v-on:submit.prevent="validateBeforeSubmit">
                    <div class="form-group row">
                        <label for="inputWarehouse" class="col-2 col-form-label">@lang('stock_opname.index.fields.warehouse')</label>
                        <div class="col-md-10">
                            <input id="inputWarehouse" type="text" v-model="newOpname.warehouse_name" class="form-control" disabled>
                            <input type="hidden" name="stock_id" v-model="newOpname.stockHId">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputProduct" class="col-2 col-form-label">@lang('stock_opname.index.fields.product')</label>
                        <div class="col-md-10">
                            <input id="inputProduct" type="text" v-model="newOpname.product_name" class="form-control" disabled>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputOpnameDate"
                               class="col-2 col-form-label">@lang('stock_opname.index.fields.opname_date')</label>
                        <div class="col-md-10">
                            <div class="input-group">
                                <flat-pickr name="opname_date" v-model="newOpname.opname_date" v-bind:config="defaultFlatPickrConfig" class="form-control"></flat-pickr>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputIsMatch"
                               class="col-2 col-form-label">@lang('stock_opname.index.fields.is_match')</label>
                        <div class="col-md-10">
                            <label class="css-control css-control-primary css-checkbox css-checkbox-rounded" for="inputIsMatch">
                                <input class="css-control-input" id="inputIsMatch" type="checkbox" v-model="newOpname.is_match" v-on:change="onChangeIsMatch" true-value="1" false-value="0">
                                <span class="css-control-indicator"></span>
                            </label>
                            <input type="hidden" name="is_match" v-model="newOpname.is_match">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputCurrentQuantity" class="col-2 col-form-label">@lang('stock_opname.index.fields.current_quantity')</label>
                        <div class="col-md-3">
                            <vue-autonumeric class="form-control text-right" v-model="newOpname.previous_quantity" v-bind:options="readOnlyNumericInput"></vue-autonumeric>
                            <input type="hidden" name="previous_quantity" v-model="newOpname.previous_quantity">
                        </div>
                        <div class="col-md-2">
                            <div class="form-control-plaintext">@{{ newOpname.baseUnit }}</div>
                        </div>
                    </div>
                    <div v-bind:class="{ 'form-group row':true, 'is-invalid':errors.has('adjusted_quantity') ? true:false }">
                        <label for="inputAdjustedQuantity"
                               class="col-2 col-form-label">@lang('stock_opname.index.fields.adjusted_quantity')</label>
                        <div class="col-md-3">
                            <vue-autonumeric class="form-control text-right" v-model="newOpname.adjusted_quantity" v-bind:options="defaultNumericConfig" v-bind:disabled="newOpname.is_match == 1 ? true:false"></vue-autonumeric>
                            <input type="hidden" name="adjusted_quantity" v-model="newOpname.adjusted_quantity">
                        </div>
                        <div class="col-md-2">
                            <div class="form-control-plaintext">@{{ newOpname.baseUnit }}</div>
                        </div>
                    </div>
                    <div v-bind:class="{ 'form-group row':true, 'is-invalid':errors.has('reason') ? true:false }">
                        <label for="inputReason" class="col-2 col-form-label">@lang('stock_opname.index.fields.reason')</label>
                        <div class="col-md-10">
                            <textarea id="inputReason" name="reason" class="form-control" rows="5" v-model="newOpname.reason"></textarea>
                            <span v-show="errors.has('reason')" class="invalid-feedback">@{{ errors.first('reason') }}</span>
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
                stock: { },
                newOpname: {
                    stockHId: '',
                    warehouse_id: '',
                    product_id: '',
                    opname_date: new Date(),
                    is_match: 0,
                    previous_quantity: 0,
                    adjusted_quantity: 0,
                    baseUnit: '',
                    reason: ''
                }
            },
            mounted: function() {
                this.mode = 'list';
                this.getAllStock();
            },
            methods: {
                validateBeforeSubmit: function() {
                    this.$validator.validateAll().then(isValid => {
                        if (!isValid) return;
                        this.errors.clear();
                        this.loadingPanel('#stockOpnameCRUDBlock', 'TOGGLE');
                        if (this.mode == 'create') {
                            axios.post(route('api.post.warehouse.stock.opname.save'), new FormData($('#stockOpnameForm')[0])).then(response => {
                                this.backToList();
                                this.loadingPanel('#stockOpnameCRUDBlock', 'TOGGLE');
                            }).catch(e => {
                                this.handleErrors(e);
                                this.loadingPanel('#stockOpnameCRUDBlock', 'TOGGLE');
                            });
                        } else { }
                    });
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
                createNew: function(idx) {
                    this.mode = 'create';
                    this.errors.clear();
                    this.stock = this.stockList[idx]
                    this.newOpname = {
                        stockHId: this.stock.hId,
                        warehouse_name: this.stock.warehouse.name,
                        product_name: this.stock.product.name,
                        opname_date: new Date(),
                        is_match: 0,
                        previous_quantity: this.stock.quantity_current,
                        adjusted_quantity: 0,
                        baseUnit: this.stock.baseUnit,
                        reason: ''
                    };
                },
                editSelected: function(index) {

                },
                backToList: function() {
                    this.mode = 'list';
                    this.errors.clear();
                    this.getAllStock();
                },
                onChangeIsMatch: function() {
                    if (this.newOpname.is_match) {
                        this.newOpname.adjusted_quantity = this.newOpname.previous_quantity;
                    }
                }
            },
            computed: {
                readOnlyNumericInput: function () {
                    var conf = Object.assign({}, this.defaultNumericConfig);

                    conf.readOnly = true;
                    conf.noEventListeners = true;

                    return conf;
                }
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
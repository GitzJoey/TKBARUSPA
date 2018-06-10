@extends('layouts.codebase.master')

@section('title')
    @lang('sales_order.index.title')
@endsection

@section('page_title')
    <span class="fa fa-cart-arrow-down fa-fw"></span>&nbsp;@lang('sales_order.index.page_title')
@endsection

@section('page_title_desc')
    @lang('sales_order.index.page_title_desc')
@endsection

@section('breadcrumbs')

@endsection

@section('custom_css')
    <style type="text/css">
        .multiselect-container .multiselect, .multiselect-container .multiselect__input, .multiselect-container .multiselect__single {
            font-size: 14px;
        }
        .multiselect-container .multiselect__input, .multiselect-container .multiselect__single {
            padding-left: 9.5px;
        }
        .multiselect-container .is-invalid .multiselect__tags {
            border-color: red;
        }
    </style>
@endsection

@section('content')
    <div id="soVue">
        @include ('layouts.common.error')
        <div class="block block-shadow-on-hover block-mode-loading-refresh" id="soListBlock">
            <div class="block-header block-header-default">
                <h3 class="block-title">@lang('sales_order.index.panel.list_panel.title')</h3>
                <div class="block-options">
                    <button type="button" class="btn-block-option" data-toggle="block-option" data-action="fullscreen_toggle"></button>
                    <button type="button" class="btn-block-option" v-on:click="renderSOListData">
                        <i class="si si-refresh"></i>
                    </button>
                    <button type="button" class="btn-block-option" data-toggle="block-option" data-action="content_toggle"></button>
                </div>
            </div>
            <div class="block-content">
                <div class="row col-3">
                    <flat-pickr id="inputSoList" v-bind:config="flatPickrConfig" v-model="selectedDate" v-on:input="renderSOListData" class="form-control"></flat-pickr>
                </div>
                <br/>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-vcenter">
                        <thead class="thead-light">
                            <tr>
                                <th class="text-center" width="15%">@lang('sales_order.index.table.list_table.header.code')</th>
                                <th class="text-center" width="15%">@lang('sales_order.index.table.list_table.header.so_date')</th>
                                <th class="text-center" width="25%">@lang('sales_order.index.table.list_table.header.customer')</th>
                                <th class="text-center" width="15%">@lang('sales_order.index.table.list_table.header.shipping_date')</th>
                                <th class="text-center" width="20%">@lang('sales_order.index.table.list_table.header.status')</th>
                                <th class="text-center" width="10%">@lang('labels.ACTION')</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-if="soList.length == 0">
                                <td colspan="6" class="text-center">@lang('labels.DATA_NOT_FOUND')</td>
                            </tr>
                            <tr v-for="(so, soIdx) in soList">
                                <td>@{{ so.code }}</td>
                                <td>@{{ so.so_created }}</td>
                                <td>@{{ so.customer_type == 'CUSTOMERTYPE.WI' ? so.walk_in_cust : so.customer.name }}
                                </td>
                                <td>@{{ so.shipping_date }}</td>
                                <td>@{{ so.statusI18n }}</td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <button class="btn btn-sm btn-secondary" v-on:click="showSelected(soIdx)" v-bind:disabled="!isFinishLoadingMounted">
                                            <span class="fa fa-info fa-fw"></span>
                                        </button>
                                        <button class="btn btn-sm btn-secondary" v-on:click="editSelected(soIdx)" v-bind:disabled="!isFinishLoadingMounted">
                                            <span class="fa fa-pencil fa-fw"></span>
                                        </button>
                                        <button class="btn btn-sm btn-secondary" v-on:click="deleteSelected(so.hId)" v-bind:disabled="!isFinishLoadingMounted">
                                            <span class="fa fa-close fa-fw"></span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="row items-push-2x text-center text-sm-left">
                    <div class="col-sm-6 col-xl-4">
                        <button type="button" class="btn btn-primary btn-lg btn-circle" data-toggle="tooltip" data-placement="top" title="{{ Lang::get('buttons.create_new_button') }}"
                                v-on:click="createNew" v-bind:disabled="!isFinishLoadingMounted">
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
        <div class="block block-shadow-on-hover block-mode-loading-refresh" id="soCRUDBlock">
            <div class="block-header block-header-default">
                <h3 class="block-title">
                    <template v-if="mode == 'create'">@lang('sales_order.index.panel.crud_panel.title_create')</template>
                    <template v-if="mode == 'show'">@lang('sales_order.index.panel.crud_panel.title_show')</template>
                    <template v-if="mode == 'edit'">@lang('sales_order.index.panel.crud_panel.title_edit')</template>
                </h3>
                <div class="block-options">
                    <button type="button" class="btn-block-option" data-toggle="block-option" data-action="fullscreen_toggle"></button>
                    <button type="button" class="btn-block-option" data-toggle="block-option" data-action="content_toggle"></button>
                </div>
            </div>
            <div class="block-content">
                <form id="soForm" method="post" v-on:submit.prevent="validateBeforeSubmit">
                    <input type="hidden" name="id" v-model="so.hId" />
                    <div class="form-group row">
                        <div class="col-6">
                            <div class="block block-shadow-on-hover block-mode-loading-refresh" id="customerListBlock">
                                <div class="block-header block-header-default">
                                    <h3 class="block-title">@lang('sales_order.index.panel.customer_panel.title')</h3>
                                    <div class="block-options">
                                        <button type="button" class="btn-block-option" data-toggle="block-option" data-action="content_toggle"></button>
                                    </div>
                                </div>
                                <div class="block-content">
                                    <div v-bind:class="{ 'form-group row':true, 'is-invalid':errors.has('customer_type') }">
                                        <label for="inputCustomerType" class="col-3 col-form-label">@lang('sales_order.fields.customer_type')</label>
                                        <div class="col-9">
                                            <template v-if="mode == 'create' || mode == 'edit'">
                                                <select id="inputCustomerType" name="customer_type" class="form-control"
                                                        v-validate="'required'" data-vv-as="{{ trans('sales_order.fields.customer_type') }}"
                                                        v-model="so.customer_type" v-on:change="onChangeCustomerType(so.customer_type)">
                                                    <option v-bind:value="defaultPleaseSelect">@lang('labels.PLEASE_SELECT')</option>
                                                    <option v-for="(ct, ctIdx) of customerTypeDDL" v-bind:value="ct.code">@{{ ct.description }}</option>
                                                </select>
                                                <span v-show="errors.has('customer_type')" class="invalid-feedback">@{{ errors.first('customer_type') }}</span>
                                            </template>
                                            <template v-if="mode == 'show'">
                                                <div class="form-control-plaintext">@{{ so.customerTypeI18n }}</div>
                                            </template>
                                        </div>
                                    </div>
                                    <template v-if="so.customer_type == 'CUSTOMERTYPE.R'">
                                        <div class="form-group row">
                                            <label for="inputCustomerId" class="col-3 col-form-label">@lang('sales_order.fields.customer_name')</label>
                                            <div class="col-md-7">
                                                <template v-if="mode == 'create' || mode == 'edit'">
                                                    <div class="multiselect-container">
                                                        <div v-bind:class="{ 'is-invalid':errors.has('customer_id') }">
                                                            <multiselect id="inputCustomerId" name="customer_id"
                                                                         v-model="so.customer"
                                                                         v-bind:options="customerDDL"
                                                                         v-validate="so.customer_type == 'CUSTOMERTYPE.R' ? 'required':''"
                                                                         data-vv-as="{{ trans('sales_order.fields.customer_name') }}"
                                                                         v-on:search-change="searchCustomerOnSearchChange"
                                                                         v-on:input="searchCustomerOnInput"
                                                                         label="name"
                                                                         track-by="hId"
                                                                         v-bind:internal-search="false"
                                                                         v-bind:show-no-results="false"
                                                                         v-bind:hide-selected="false"
                                                                         v-bind:loading="searchCustomerLoading">
                                                            </multiselect>
                                                            <label class="typo__label form__label invalid-feedback" v-show="errors.has('customer_id')">@{{ errors.first('customer_id') }}</label>
                                                        </div>
                                                    </div>
                                                </template>
                                                <template v-if="mode == 'show'">
                                                    <div class="form-control-plaintext">@{{ so.customer.name }}</div>
                                                </template>
                                            </div>
                                            <div class="col-sm-2">
                                                <button id="customerDetailButton" type="button" class="btn btn-primary btn-sm"
                                                        data-toggle="modal" data-target="#customerDetailModal"
                                                        v-bind:disabled="so.customerHId == ''">
                                                    <span class="fa fa-info-circle fa-lg"></span>
                                                </button>
                                            </div>
                                        </div>
                                    </template>
                                    <template v-if="so.customer_type == 'CUSTOMERTYPE.WI'">
                                        <div v-bind:class="{ 'form-group row':true, 'is-invalid':errors.has('walk_in_cust') }">
                                            <label for="inputCustomerName" class="col-3 col-form-label">@lang('sales_order.fields.customer_name')</label>
                                            <div class="col-md-9">
                                                <template v-if="mode == 'create' || mode == 'edit'">
                                                    <input type="text" id="inputCustomerName" name="walk_in_cust"
                                                           v-validate="so.customer_type == 'CUSTOMERTYPE.WI' ? 'required':''"
                                                           data-vv-as="{{ trans('sales_order.fields.customer_name') }}"
                                                           class="form-control" v-model="so.customer_name">
                                                    <span v-show="errors.has('walk_in_cust')" class="invalid-feedback">@{{ errors.first('walk_in_cust') }}</span>
                                                </template>
                                                <template v-if="mode == 'show'">
                                                    <div class="form-control-plaintext">@{{ so.walk_in_cust }}</div>
                                                </template>
                                            </div>
                                        </div>
                                        <div v-bind:class="{ 'form-group row':true, 'has-error':errors.has('walk_in_cust_detail') }">
                                            <label for="inputCustomerDetails" class="col-3 col-form-label">@lang('sales_order.fields.customer_details')</label>
                                            <div class="col-md-9">
                                                <template v-if="mode == 'create' || mode == 'edit'">
                                                    <textarea id="inputCustomerDetails" name="walk_in_cust_detail" class="form-control" rows="5"
                                                              v-validate="so.customer_type == 'CUSTOMERTYPE.WI' ? 'required':''"
                                                              data-vv-as="{{ trans('sales_order.fields.customer_details') }}"
                                                              v-model="so.customer_details"></textarea>
                                                    <span v-show="errors.has('walk_in_cust_detail')" class="invalid-feedback">@{{ errors.first('walk_in_cust_detail') }}</span>
                                                </template>
                                                <template v-if="mode == 'show'">
                                                    <div class="form-control-plaintext">@{{ so.customer_details }}</div>
                                                </template>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="block block-shadow-on-hover block-mode-loading-refresh" id="soDetailBlock">
                                <div class="block-header block-header-default">
                                    <h3 class="block-title">@lang('sales_order.index.panel.detail_panel.title')</h3>
                                    <div class="block-options">
                                        <button type="button" class="btn-block-option" data-toggle="block-option" data-action="content_toggle"></button>
                                    </div>
                                </div>
                                <div class="block-content">
                                    <div class="form-group row">
                                        <label for="inputSoCode" class="col-3 col-form-label">@lang('sales_order.fields.so_code')</label>
                                        <div class="col-md-9">
                                            <template v-if="mode == 'create' || mode == 'edit'">
                                                <input type="text" class="form-control" id="inputSoCode" name="code" v-model="so.code" placeholder="SO Code" readonly>
                                            </template>
                                            <template v-if="mode == 'show'">
                                                <div class="form-control-plaintext">@{{ so.code }}</div>
                                            </template>
                                        </div>
                                    </div>
                                    <div v-bind:class="{ 'form-group row':true, 'is-invalid':errors.has('so_type') }">
                                        <label for="inputSoType" class="col-3 col-form-label">@lang('sales_order.fields.so_type')</label>
                                        <div class="col-md-9">
                                            <template v-if="mode == 'create' || mode == 'edit'">
                                                <select id="inputSoType" name="so_type" class="form-control"
                                                        v-validate="'required'"
                                                        data-vv-as="{{ trans('sales_order.fields.so_type') }}"
                                                        v-model="so.so_type">
                                                    <option v-bind:value="defaultPleaseSelect">@lang('labels.PLEASE_SELECT')</option>
                                                    <option v-for="(soType, soTypeIdx) of soTypeDDL" v-bind:value="soType.code">@{{ soType.description }}</option>
                                                </select>
                                                <span v-show="errors.has('so_type')" class="invalid-feedback" v-cloak>@{{ errors.first('so_type') }}</span>
                                            </template>
                                            <template v-if="mode == 'show'">
                                                <div class="form-control-plaintext">@{{ so.soTypeI18n }}</div>
                                            </template>
                                        </div>
                                    </div>
                                    <div v-bind:class="{ 'form-group row':true, 'is-invalid':errors.has('so_created') }">
                                        <label for="inputSoCreated" class="col-3 col-form-label">@lang('sales_order.fields.so_created')</label>
                                        <div class="col-md-9">
                                            <template v-if="mode == 'create' || mode == 'edit'">
                                                <flat-pickr id="inputSoCreated" name="so_created" class="form-control"
                                                            v-bind:config="defaultFlatPickrConfig"
                                                            v-model="so.so_created" v-validate="'required'"
                                                            data-vv-as="{{ trans('sales_order.fields.so_created') }}"></flat-pickr>
                                            </template>
                                            <template v-if="mode == 'show'">
                                                <div class="form-control-plaintext">@{{ so.so_created }}</div>
                                            </template>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="inputSoStatus" class="col-3 col-form-label">@lang('sales_order.fields.so_status')</label>
                                        <div class="col-sm-9">
                                            <template v-if="mode == 'create' || mode == 'edit'">
                                                <div class="form-control-plaintext">@{{ soStatusDesc }}</div>
                                                <input type="hidden" name="po_status" v-model="so.status"/>
                                            </template>
                                            <template v-if="mode == 'show'">
                                                <div class="form-control-plaintext">@{{ soStatusDesc }}</div>
                                            </template>
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
                                    <h3 class="block-title">@lang('sales_order.index.panel.shipping_panel.title')</h3>
                                    <div class="block-options">
                                        <button type="button" class="btn-block-option" data-toggle="block-option" data-action="content_toggle"></button>
                                    </div>
                                </div>
                                <div class="block-content">
                                    <div v-bind:class="{ 'form-group row':true, 'is-invalid':errors.has('shipping_date') }">
                                        <label for="inputShippingDate" class="col-3 col-form-label">@lang('sales_order.fields.shipping_date')</label>
                                        <div class="col-md-9">
                                            <template v-if="mode == 'create' || mode == 'edit'">
                                                <flat-pickr id="inputShippingDate" name="shipping_date" class="form-control"
                                                            v-model="so.shipping_date" v-bind:config="defaultFlatPickrConfig"
                                                            v-validate="'required'" data-vv-as="{{ trans('sales_order.fields.shipping_date') }}"></flat-pickr>
                                                <span v-show="errors.has('shipping_date')" class="invalid-feedback">@{{ errors.first('shipping_date') }}</span>
                                            </template>
                                            <template v-if="mode == 'show'">
                                                <div class="form-control-plaintext">@{{ so.shipping_date }}</div>
                                            </template>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="form-group row">
                                        <label for="inputVendorTrucking" class="col-3 col-form-label">@lang('sales_order.fields.vendor_trucking')</label>
                                        <div class="col-md-9">
                                            <template v-if="mode == 'create' || mode == 'edit'">
                                                <select id="inputVendorTrucking" name="vendor_trucking_id" class="form-control"
                                                        v-model="so.vendorTruckingHId">
                                                    <option v-bind:value="defaultPleaseSelect">@lang('labels.PLEASE_SELECT')</option>
                                                    <option v-for="(vendorTrucking, vendorTruckingIdx) of vendorTruckingDDL" v-bind:value="vendorTrucking.hId">@{{ vendorTrucking.name }}</option>
                                                </select>
                                            </template>
                                            <template v-if="mode == 'show'">
                                                <div class="form-control-plaintext">@{{ so.vendor_trucking ? so.vendor_trucking.name:'' }}</div>
                                            </template>
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
                                    <h3 class="block-title">@lang('sales_order.index.panel.transaction_panel.title')</h3>
                                    <div class="block-options">
                                        <button type="button" class="btn-block-option" data-toggle="block-option" data-action="content_toggle"></button>
                                    </div>
                                </div>
                                <div class="block-content">
                                    <div class="row">
                                        <div class="col-12">
                                            <template v-if="mode == 'create' || mode == 'edit'">
                                                <multiselect id="selectProduct" v-model="productSelected" v-bind:options="product_options" v-on:input="productSelectedOnInput">
                                                    <template slot="singleLabel" slot-scope="props">
                                                        @{{ props.option.product_name }}
                                                    </template>
                                                    <template slot="option" slot-scope="props">
                                                        @{{ props.option.product_name }}<br/>
                                                        <small>
                                                            <strong>@lang('sales_order.fields.transaction_type'):</strong>&nbsp;@{{ props.option.product_type }}&nbsp;&nbsp;&nbsp;
                                                            <template v-if="props.option.in_stock">
                                                                <strong>@lang('sales_order.fields.transaction_in_stock'):</strong>&nbsp;<template v-if="props.option.in_stock">@lang('sales_order.fields.transaction_in_stock_yes')</template><template v-else>@lang('sales_order.fields.transaction_in_stock_no')</template>&nbsp;&nbsp;&nbsp;
                                                                <strong>@lang('sales_order.fields.transaction_warehouse_name'):</strong>&nbsp;@{{ props.option.warehouse_name }}&nbsp;&nbsp;&nbsp;
                                                                <strong>@lang('sales_order.fields.transaction_in_stock_date'):</strong>&nbsp;@{{ props.option.in_stock_date }}&nbsp;&nbsp;&nbsp;
                                                                <strong>@lang('sales_order.fields.transaction_total'):</strong>&nbsp;@{{ props.option.base_total }} @{{ props.option.base_unit }}
                                                            </template>
                                                        </small>
                                                    </template>
                                                    <template slot="noResult">@lang('labels.DATA_NOT_FOUND')</template>
                                                </multiselect>
                                            </template>
                                            <template v-if="mode == 'show'">
                                            </template>
                                        </div>
                                    </div>
                                    <br/>
                                    <div class="table-responsive">
                                        <table id="itemsListTable" class="table table-bordered table-striped table-vcenter">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th colspan="8">@lang('sales_order.index.table.item_table.header.title')</th>
                                                </tr>
                                                <tr>
                                                    <th>@lang('sales_order.index.table.item_table.header.product_name')</th>
                                                    <th class="text-center">@lang('sales_order.index.table.item_table.header.quantity')</th>
                                                    <th class="text-center">@lang('sales_order.index.table.item_table.header.unit')</th>
                                                    <th class="text-center">@lang('sales_order.index.table.item_table.header.price_unit')</th>
                                                    <th class="text-center">@lang('sales_order.index.table.item_table.header.discount')</th>
                                                    <th>&nbsp;</th>
                                                    <th class="text-center">@lang('sales_order.index.table.item_table.header.total_price')</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-if="so.items.length == 0">
                                                    <td colspan="8" class="text-center">@lang('labels.DATA_NOT_FOUND')</td>
                                                </tr>
                                                <tr v-for="(i, iIdx) in so.items">
                                                    <td>
                                                        <template v-if="mode == 'create' || mode == 'edit'">
                                                            @{{ i.product.name }}
                                                            <input type="hidden" name="item_product_id[]" v-model="i.product.hId"/>
                                                        </template>
                                                        <template v-if="mode == 'show'">
                                                            <div class="form-control-plaintext">@{{ i.product.name }}</div>
                                                        </template>
                                                        <input type="hidden" name="item_id[]" v-model="i.hId">
                                                        <input type="hidden" name="item_stock_id[]" v-model="i.stockHId">
                                                    </td>
                                                    <td width="5%">
                                                        <template v-if="mode == 'create' || mode == 'edit'">
                                                            <vue-autonumeric v-bind:class="{ 'form-control text-align-right':true, 'is-invalid':errors.has('quantity_' + iIdx) }"
                                                                             v-bind:options="defaultNumericConfig"
                                                                             v-bind:data-vv-name="'quantity_' + iIdx"
                                                                             v-bind:data-vv-as="'{{ trans('sales_order.index.table.item_table.header.quantity') }} ' + (iIdx + 1)"
                                                                             v-model="i.quantity" v-validate="'required'"></vue-autonumeric>
                                                            <input type="hidden" name="item_quantity[]" v-model="i.quantity">
                                                        </template>
                                                        <template v-if="mode == 'show'">
                                                            <div class="form-control-plaintext text-align-right"><vue-autonumeric v-bind:tag="'span'" v-model="i.quantity" v-bind:options="numericFormatToString"></vue-autonumeric></div>
                                                        </template>
                                                    </td>
                                                    <td width="15%">
                                                        <template v-if="mode == 'create' || mode == 'edit'">
                                                            <select v-bind:class="{ 'form-control':true, 'is-invalid':errors.has('product_unit_' + iIdx) }"
                                                                    name="item_selected_product_unit_id[]"
                                                                    v-model="i.selected_product_unit.hId"
                                                                    v-bind:data-vv-name="'product_unit_' + iIdx"
                                                                    v-bind:data-vv-as="'{{ trans('sales_order.index.table.item_table.header.unit') }} ' + (iIdx + 1)"
                                                                    v-validate="'required'"
                                                                    v-on:change="onChangeProductUnit(iIdx)">
                                                                <option v-bind:value="defaultPleaseSelect">@lang('labels.PLEASE_SELECT')</option>
                                                                <option v-for="(pu, puIdx) in i.product.product_units" v-bind:value="pu.hId">@{{ pu.unit.unitName }}</option>
                                                            </select>
                                                        </template>
                                                        <template v-if="mode == 'show'">
                                                            <div class="form-control-plaintext">@{{ i.selected_product_unit.unit.name }}</div>
                                                        </template>
                                                        <input type="hidden" name="conversion_value[]" v-model="i.conversion_value">
                                                        <input type="hidden" name="base_product_unit_id[]" v-model="i.base_product_unit.hId">
                                                    </td>
                                                    <td width="13%">
                                                        <template v-if="mode == 'create' || mode == 'edit'">
                                                            <vue-autonumeric class="form-control text-align-right"
                                                                             v-model="i.price" v-validate="'required'"
                                                                             v-bind:options="defaultCurrencyConfig"
                                                                             v-bind:data-vv-name="'price_' + iIdx"
                                                                             v-bind:data-vv-as="'{{ trans('sales_order.index.table.item_table.header.price_unit') }} ' + (iIdx + 1)"></vue-autonumeric>
                                                            <input type="hidden" name="item_price[]" v-model="i.price">
                                                        </template>
                                                        <template v-if="mode == 'show'">
                                                            <div class="form-control-plaintext text-align-right"><vue-autonumeric v-bind:tag="'span'" v-model="i.price" v-bind:options="currencyFormatToString"></vue-autonumeric></div>
                                                        </template>
                                                    </td>
                                                    <td width="10%">
                                                        <template v-if="mode == 'create' || mode == 'edit'">
                                                            <vue-autonumeric class="form-control text-align-right"
                                                                             v-model="i.discount"
                                                                             v-bind:options="defaultCurrencyConfig"></vue-autonumeric>
                                                            <input type="hidden" name="item_discount[]" v-model="i.discount">
                                                        </template>
                                                        <template v-if="mode == 'show'">
                                                            <div class="form-control-plaintext text-align-right"><vue-autonumeric v-bind:tag="'span'" v-model="i.discount" v-bind:options="currencyFormatToString"></vue-autonumeric></div>
                                                        </template>
                                                    </td>
                                                    <td width="3%">
                                                        <template v-if="mode == 'create' || mode == 'edit'">
                                                            <button type="button" class="btn btn-danger btn-md" v-on:click="removeItem(iIdx)"><span class="fa fa-minus"></span></button>
                                                        </template>
                                                        <template v-if="mode == 'show'">
                                                        </template>
                                                    </td>
                                                    <td width="12%" class="text-align-right">
                                                        <vue-autonumeric v-bind:tag="'span'" v-bind:options="currencyFormatToString" v-model="i.total"></vue-autonumeric>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="table-responsive">
                                        <table id="expensesListTable" class="table table-bordered table-striped table-vcenter">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th colspan="5">@lang('sales_order.index.table.expense_table.header.title')</th>
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
                                                    <th>@lang('sales_order.index.table.expense_table.header.name')</th>
                                                    <th width="15%"
                                                        class="text-center">@lang('sales_order.index.table.expense_table.header.type')</th>
                                                    <th width="10%"
                                                        class="text-center">@lang('sales_order.index.table.expense_table.header.internal_expense')</th>
                                                    <th width="30%"
                                                        class="text-center">@lang('sales_order.index.table.expense_table.header.remarks')</th>
                                                    <th width="3%">&nbsp;</th>
                                                    <th width="12%"
                                                        class="text-center">@lang('sales_order.index.table.expense_table.header.amount')</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-if="so.expenses.length == 0">
                                                    <td colspan="6" class="text-center">@lang('labels.DATA_NOT_FOUND')</td>
                                                </tr>
                                                <tr v-for="(expense, expenseIndex) in so.expenses">
                                                    <td v-bind:class="{ 'is-invalid':errors.has('expense_name_' + expenseIndex) }">
                                                        <template v-if="mode == 'create' || mode == 'edit'">
                                                            <input name="expense_name[]" type="text" class="form-control"
                                                                   v-model="expense.name" v-validate="'required'" v-bind:data-vv-as="'{{ trans('sales_order.index.table.expense_table.header.name') }} ' + (expenseIndex + 1)"
                                                                   v-bind:data-vv-name="'expense_name_' + expenseIndex">
                                                        </template>
                                                        <template v-if="mode == 'show'">
                                                            <div class="form-control-plaintext">@{{ expense.name }}</div>
                                                        </template>
                                                        <input type="hidden" name="expense_id[]" v-model="expense.hId" />
                                                    </td>
                                                    <td v-bind:class="{ 'is-invalid':errors.has('expense_type_' + expenseIndex) }">
                                                        <template v-if="mode == 'create' || mode == 'edit'">
                                                            <select class="form-control" v-model="expense.type" name="expense_type[]"
                                                                    v-validate="'required'" v-bind:data-vv-as="'{{ trans('sales_order.index.table.expense_table.header.type') }} ' + (expenseIndex + 1)"
                                                                    v-bind:data-vv-name="'expense_type_' + expenseIndex">
                                                                <option v-bind:value="defaultPleaseSelect">@lang('labels.PLEASE_SELECT')</option>
                                                                <option v-for="(expenseType, expenseTypeIdx) in expenseTypeDDL" v-bind:value="expenseType.code">@{{ expenseType.description }}</option>
                                                            </select>
                                                        </template>
                                                        <template v-if="mode == 'show'">
                                                            <div class="form-control-plaintext">@{{ expense.typeI18n }}</div>
                                                        </template>
                                                    </td>
                                                    <td class="text-center">
                                                        <template v-if="mode == 'create' || mode == 'edit'">
                                                            <input type="checkbox" v-model="expense.is_internal_expense" true-value="1" false-value="0">
                                                        </template>
                                                        <template v-if="mode == 'show'">
                                                            <input type="checkbox" v-model="expense.is_internal_expense" true-value="1" false-value="0" disabled>
                                                        </template>
                                                    </td>
                                                    <td>
                                                        <template v-if="mode == 'create' || mode == 'edit'">
                                                            <input name="expense_remarks[]" type="text" class="form-control" v-model="expense.remarks"/>
                                                        </template>
                                                        <template v-if="mode == 'show'">
                                                            <div class="form-control-plaintext">@{{ expense.remarks }}</div>
                                                        </template>
                                                    </td>
                                                    <td class="text-center">
                                                        <template v-if="mode == 'create' || mode == 'edit'">
                                                            <button type="button" class="btn btn-danger btn-md" v-on:click="removeExpense(expenseIndex)">
                                                                <span class="fa fa-minus"></span>
                                                            </button>
                                                        </template>
                                                        <template v-if="mode == 'show'">
                                                        </template>
                                                    </td>
                                                    <td v-bind:class="{ 'is-invalid':errors.has('expense_amount_' + expenseIndex) }">
                                                        <template v-if="mode == 'create' || mode == 'edit'">
                                                            <vue-autonumeric class="form-control text-align-right"
                                                                             v-model="expense.amount" v-validate="'required'"
                                                                             v-bind:options="defaultCurrencyConfig"
                                                                             v-bind:data-vv-as="'{{ trans('sales_order.index.table.expense_table.header.amount') }} ' + (expenseIndex + 1)"
                                                                             v-bind:data-vv-name="'expense_amount_' + expenseIndex"><</vue-autonumeric>
                                                            <input type="hidden" name="expense_amount[]" v-model="expense.amount">
                                                        </template>
                                                        <template v-if="mode == 'show'">
                                                            <div class="form-control-plaintext text-align-right"><vue-autonumeric v-bind:tag="'span'" v-model="expense.amount" v-bind:options="currencyFormatToString"></vue-autonumeric></div>
                                                        </template>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="table-responsive">
                                        <table id="itemsTotalListTable" class="table table-bordered table-vcenter">
                                            <tbody>
                                                <tr>
                                                    <td colspan="7" class="text-align-right">@lang('sales_order.index.table.total_table.header.subtotal')</td>
                                                    <td width="12%" class="text-align-right">
                                                        <vue-autonumeric v-bind:tag="'span'" v-bind:options="currencyFormatToString" v-model="so.subtotal"></vue-autonumeric>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="7" class="text-align-right">@lang('sales_order.index.table.total_table.header.discount')</td>
                                                    <td width="12%">
                                                        <template v-if="mode == 'create' || mode == 'edit'">
                                                            <vue-autonumeric class="form-control text-align-right" v-model="so.discount" v-bind:options="defaultCurrencyConfig"></vue-autonumeric>
                                                            <input type="hidden" name="discount" v-model="so.discount">
                                                        </template>
                                                        <template v-if="mode == 'show'">
                                                            <div class="form-control-plaintext text-align-right"><vue-autonumeric v-bind:tag="'span'" v-bind:options="currencyFormatToString" v-model="so.discount"></vue-autonumeric></div>
                                                        </template>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="7" class="text-align-right">@lang('sales_order.index.table.total_table.header.grandtotal')</td>
                                                    <td width="12%" class="text-align-right">
                                                        <vue-autonumeric v-bind:tag="'span'" v-bind:options="currencyFormatToString" v-model="so.grandtotal"></vue-autonumeric>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-12">
                            <div class="block block-shadow-on-hover block-mode-loading-refresh" id="remarksListBlock">
                                <div class="block-header block-header-default">
                                    <h3 class="block-title">@lang('sales_order.index.panel.remarks_panel.title')</h3>
                                    <div class="block-options">
                                        <button type="button" class="btn-block-option" data-toggle="block-option" data-action="content_toggle"></button>
                                    </div>
                                </div>
                                <div class="block-content">
                                    <div>
                                        <ul class="nav nav-tabs nav-tabs-alt" data-toggle="tabs" role="tablist">
                                            <li class="nav-item">
                                                <a class="nav-link active" href="#tabs_remarks">@lang('sales_order.index.tabs.remarks')</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" href="#tabs_internal">@lang('sales_order.index.tabs.internal')</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" href="#tabs_private">@lang('sales_order.index.tabs.private')</a>
                                            </li>
                                        </ul>
                                        <div class="block-content tab-content overflow-hidden">
                                            <div class="tab-pane fade fade-up show active" id="tabs_remarks" role="tabpanel">
                                                <template v-if="mode == 'create' || mode == 'edit'">
                                                    <textarea id="inputRemarks" name="remarks" class="form-control" rows="5" v-model="so.remarks"></textarea>
                                                </template>
                                                <template v-if="mode == 'show'">
                                                    <div class="form-control-plaintext">@{{ so.remarks }}</div>
                                                </template>
                                            </div>
                                            <div class="tab-pane fade fade-up show" id="tabs_internal" role="tabpanel">
                                                <template v-if="mode == 'create' || mode == 'edit'">
                                                    <textarea id="inputInternalRemarks" name="internal_remarks" class="form-control" rows="5" v-model="so.internal_remarks"></textarea>
                                                </template>
                                                <template v-if="mode == 'show'">
                                                    <div class="form-control-plaintext">@{{ so.internal_remarks }}</div>
                                                </template>
                                            </div>
                                            <div class="tab-pane fade fade-up show" id="tabs_private" role="tabpanel">
                                                <template v-if="mode == 'create' || mode == 'edit'">
                                                    <textarea id="inputPrivateRemarks" name="private_remarks" class="form-control" rows="5" v-model="so.private_remarks"></textarea>
                                                </template>
                                                <template v-if="mode == 'show'">
                                                    <div class="form-control-plaintext">@{{ so.private_remarks }}</div>
                                                </template>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-12 text-center">
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

        @include('sales_order.customer')
    </div>
@endsection

@section('ziggy')
    @routes('sales_order')
@endsection

@section('custom_js')
    <script type="application/javascript">
        var soVue = new Vue ({
            el: '#soVue',
            data: {
                soList: [],
                customerTypeDDL: [],
                soTypeDDL: [],
                customerDDL: [],
                vendorTruckingDDL: [],
                expenseTypeDDL: [],
                selectedDate: new Date(),
                selectedCustomer: {},
                soStatusDesc: '',
                product_options: [],
                productSelected: '',
                isFinishLoadingMounted: false,
                searchCustomerLoading: false,
                productSelectedLoading: false,
                allStockAndProduct: [],
                mode: '',
                so: {
                    items:[],
                    expenses: [],
                    customer: '',
                    price_level: {},
                    discount: 0,
                    subtotal: 0,
                    grandtotal: 0
                },
                allSODates: []
            },
            mounted: function () {
                this.mode = 'list';
                this.renderSOListData();

                Promise.all([
                    this.getCustomerType(),
                    this.getSOType(),
                    this.getVendorTrucking(),
                    this.getAllStockAndProduct(),
                    this.getExpenseType()
                ]).then(() => {
                    this.isFinishLoadingMounted = true;
                });
            },
            methods: {
                validateBeforeSubmit: function () {
                    this.$validator.validateAll().then(isValid => {
                        if (!isValid) return;
                        this.errors.clear();
                        this.loadingPanel('#soCRUDBlock', 'TOGGLE');
                        if (this.mode == 'create') {
                            axios.post(route('api.post.so.save'), new FormData($('#soForm')[0])).then(response => {
                                this.backToList();
                                this.loadingPanel('#soCRUDBlock', 'TOGGLE');
                            }).catch(e => {
                                this.handleErrors(e);
                                this.loadingPanel('#soCRUDBlock', 'TOGGLE');
                            });
                        } else if (this.mode == 'edit') {
                            axios.post(route('api.post.so.edit', this.so.hId), new FormData($('#soForm')[0])).then(response => {
                                this.backToList();
                                this.loadingPanel('#soCRUDBlock', 'TOGGLE');
                            }).catch(e => {
                                this.handleErrors(e);
                                this.loadingPanel('#soCRUDBlock', 'TOGGLE');
                            });
                        } else { }
                    });
                },
                renderSOListData: function () {
                    this.loadingPanel('#soListBlock', 'TOGGLE');

                    var seldate = moment(this.selectedDate).formatPHP(this.databaseDateFormat);

                    Promise.all([
                        this.getAllSOData(seldate)
                    ]).then(() => {
                        this.loadingPanel('#soListBlock', 'TOGGLE');
                    });
                },
                getAllSOData: function(date) {
                    return new Promise((resolve, reject) => {
                        var qS = [];
                        qS.push({'key': 'date', 'value': date});

                        axios.get(route('api.get.so.read').url() + this.generateQueryStrings(qS)).then(response => {
                            this.soList = response.data;
                            resolve(true);
                        }).catch(e => {
                            this.handleErrors(e);
                            reject(e.response.data.message);
                        });
                    });
                },
                onChangeCustomerType: function (type) {
                    if (type == 'CUSTOMERTYPE.WI') {
                        this.so.customerHId = '';
                    }
                },
                createNew: function () {
                    this.mode = 'create';
                    this.errors.clear();
                    this.so = this.emptySO();
                },
                editSelected: function (idx) {
                    this.mode = 'edit';
                    this.errors.clear();
                    this.so  = _.merge({
                        discount: 0,
                        subtotal: 0,
                        grandtotal: 0,
                        items: []
                    }, this.soList[idx]);
                },
                showSelected: function (idx) {
                    this.mode = 'show';
                    this.errors.clear();
                    this.so  = _.merge({
                        discount: 0,
                        subtotal: 0,
                        grandtotal: 0,
                        items: []
                    }, this.soList[idx]);
                },
                deleteSelected: function (idx) {
                    axios.post('/api/post/so/delete/' + idx).then(response => {
                        this.backToList();
                    }).catch(e => {
                        this.handleErrors(e);
                    });
                },
                backToList: function () {
                    this.mode = 'list';
                    this.errors.clear();
                    this.renderSOListData();
                },
                emptySO: function () {
                    return {
                        hId: '',
                        code: this.generateSOCode(),
                        so_created: new Date(),
                        shipping_date: new Date(),
                        customer_type: '',
                        customerHId: '',
                        vendorTruckingHId: '',
                        so_type: '',
                        status: 'SOSTATUS.D',
                        productHId: '',
                        items: [],
                        expenses: [],
                        customer: '',
                        price_level: { },
                        discount: 0,
                        subtotal: 0,
                        grandtotal: 0
                    }
                },
                productSelectedOnInput() {
                    this.insertItem(this.productSelected);

                    this.productSelected = '';
                },
                insertItem: function (productSelected) {
                    this.so.items.push({
                        stockHId: productSelected.stock_id,
                        product: productSelected.product,
                        selected_product_unit: this.defaultProductUnit(),
                        base_product_unit: _.cloneDeep(_.find(productSelected.product.product_units, { is_base: 1 })),
                        quantity: 0,
                        price: 0,
                        discount: 0,
                        total: 0
                    });
                },
                removeItem: function (index) {
                    this.so.items.splice(index, 1);
                },
                addExpense: function () {
                    this.so.expenses.push({
                        hId: '',
                        name: '',
                        type: '',
                        is_internal_expense: 0,
                        amount: 0,
                        remarks: ''
                    });
                },
                removeExpense: function (index) {
                    this.so.expenses.splice(index, 1);
                },
                defaultProductUnit: function(){
                    return {
                        hId: '',
                        unit: {
                            hId: ''
                        },
                        conversion_value: 1
                    };
                },
                onChangeProductUnit: function(itemIndex) {
                    if (this.so.items[itemIndex].selected_product_unit.hId == '') {
                        this.so.items[itemIndex].selected_product_unit = this.defaultProductUnit();
                    } else {
                        var pUnit = _.find(this.so.items[itemIndex].product.product_units, { hId: this.so.items[itemIndex].selected_product_unit.hId });
                        _.merge(this.so.items[itemIndex].selected_product_unit, pUnit);
                    }
                },
                getCustomerType: function() {
                    return new Promise((resolve, reject) => {
                        axios.get(route('api.get.lookup.bycategory', 'CUSTOMER_TYPE').url()).then(
                            response => {
                                this.customerTypeDDL = response.data;
                                resolve(true);
                            }
                        ).catch(e => {
                            this.handleErrors(e);
                            reject(e.response.data.message);
                        });
                    });
                },
                getSOType: function() {
                    return new Promise((resolve, reject) => {
                        axios.get(route('api.get.lookup.bycategory', 'SO_TYPE').url()).then(
                            response => {
                                this.soTypeDDL = response.data;
                                resolve(true);
                            }
                        ).catch(e => {
                            this.handleErrors(e);
                            reject(e.response.data.message);
                        });
                    });
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
                getAllStockAndProduct: function() {
                    return new Promise((resolve, reject) => {
                        axios.get(route('api.get.warehouse.stock.all.current.stock.and.product').url()).then(response => {
                            this.allStockAndProduct = response.data;
                            resolve(true);
                        }).catch(e => {
                            this.handleErrors(e);
                            reject(e.response.data.message);
                        });
                    });
                },
                generateSOCode: function() {
                    axios.get(route('api.get.so.generate.so_code').url()).then(
                        response => { this.so.code = response.data; }
                    );
                },
                getExpenseType: function() {
                    axios.get(route('api.get.lookup.bycategory', 'EXPENSE_TYPE').url()).then(
                        response => { this.expenseTypeDDL = response.data; }
                    );
                },
                calculateTotal: function() {
                    var allItemTotal = 0;
                    _.forEach(this.so.items, function(item, key) {
                        var itemTotal = 0;
                        itemTotal = (item.selected_product_unit.conversion_value * item.quantity * item.price) - item.discount;
                        item.total = itemTotal;

                        allItemTotal += itemTotal;
                    });

                    var expenseTotal = 0;
                    _.forEach(this.so.expenses, function (expense, key) {
                        if (expense.type.code === 'EXPENSETYPE.ADD')
                            expenseTotal += expense.amount;
                        else
                            expenseTotal -= expense.amount;
                    });

                    this.so.subtotal = allItemTotal + expenseTotal;
                    this.so.grandtotal = this.so.subtotal - this.so.discount;
                },
                populateCustomer: function(id) {
                    this.so.customer = _.cloneDeep(_.find(this.customerDDL, { hId: id }));
                },
                searchCustomerOnSearchChange: function(query) {
                    if (query != '') {
                        this.searchCustomerLoading = true;
                        axios.get(route('api.get.customer.search', query).url()).then(
                            response => {
                                this.customerDDL = response.data;
                                this.searchCustomerLoading = false;
                            }
                        );
                    }
                },
                searchCustomerOnInput: function() {
                    if (this.so.customer == null) {
                        this.so.customerHId = '';
                    } else {
                        this.selectedCustomer = this.so.customer;
                        this.so.customerHId = this.so.customer.hId;
                    }
                }
            },
            watch: {
                'so.customerHId': function() {
                    if (this.so.customerHId == '') {
                        this.selectedCustomer = {};
                    } else {
                        this.selectedCustomer = this.so.customer;
                    }
                },
                'so.so_type': function() {
                    if (this.so.so_type == 'SOTYPE.S') {
                        this.product_options = _.filter(this.allStockAndProduct, { in_stock: 1 });
                    } else if (this.so.so_type == 'SOTYPE.SVC') {
                        this.product_options = _.filter(this.allStockAndProduct, { in_stock: 0 });
                    } else if (this.so.so_type == 'SOTYPE.AC') {
                        this.product_options = _.filter(this.allStockAndProduct, { in_stock: 1 });
                    } else {
                        this.product_options = [];
                    }
                },
                'so.status': function() {
                    if (this.so.status != '') {
                        axios.get(route('api.get.lookup.description.byvalue', 'SOSTATUS.D').url()).then(
                            response => { this.soStatusDesc = response.data; }
                        );
                    }
                },
                'so.items': {
                    deep: true,
                    handler: function(oldVal, newVal) {
                        this.calculateTotal();
                    }
                },
                'so.expenses': {
                    deep: true,
                    handler: function(oldVal, newVal) {
                        this.calculateTotal();
                    }
                },
                mode: function() {
                    switch (this.mode) {
                        case 'create':
                        case 'edit':
                        case 'show':
                            this.contentPanel('#soListBlock', 'CLOSE')
                            this.contentPanel('#soCRUDBlock', 'OPEN')
                            break;
                        case 'list':
                        default:
                            this.contentPanel('#soListBlock', 'OPEN')
                            this.contentPanel('#soCRUDBlock', 'CLOSE')
                            break;
                    }
                }
            },
            computed: {
                flatPickrConfig: function() {
                    var conf = Object.assign({}, this.defaultFlatPickrConfig);

                    conf.altFormat = 'd M Y',
                    conf.enableTime = false;

                    return conf;
                },
                percentageFormatToString: function() {
                    var conf = Object.assign({}, this.defaultPercentageConfig);

                    conf.readOnly = true;
                    conf.noEventListeners = true;

                    return conf;
                },
                numericFormatToString: function() {
                    var conf = Object.assign({}, this.defaultNumericConfig);

                    conf.readOnly = true;
                    conf.noEventListeners = true;

                    return conf;
                },
                currencyFormatToString: function() {
                    var conf = Object.assign({}, this.defaultCurrencyConfig);

                    conf.readOnly = true;
                    conf.noEventListeners = true;

                    return conf;
                },
                defaultPleaseSelect: function() {
                    return '';
                }
            }
        });
    </script>
    <script type="application/javascript" src="{{ mix('js/apps/so.js') }}"></script>
@endsection
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

@section('custom_css')
    <style type="text/css">
        .hideTextBox {
            display: none;
        }
    </style>
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
                <div class="row col-3">
                    <flat-pickr id="inputPoList" v-bind:config="flatPickrInlineConfig" v-model="selectedDate" v-on:on-change="getAllPO(selectedDate)" class="form-control"></flat-pickr>
                </div>
                <br/>
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
                            <tr v-for="(po, poIdx) in poList">
                                <td>@{{ po.code }}</td>
                                <td>@{{ po.po_created }}</td>
                                <td></td>
                                <td></td>
                                <td>@{{ po.statusI18n }}</td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <button class="btn btn-sm btn-secondary" v-on:click="showSelected(cIdx)" v-bind:disabled="!isFinishLoadingMounted">
                                            <span class="fa fa-info fa-fw"></span>
                                        </button>
                                        <button class="btn btn-sm btn-secondary" v-on:click="editSelected(cIdx)" v-bind:disabled="!isFinishLoadingMounted">
                                            <span class="fa fa-pencil fa-fw"></span>
                                        </button>
                                        <button class="btn btn-sm btn-secondary" v-on:click="deleteSelected(c.hId)" v-bind:disabled="!isFinishLoadingMounted">
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
                                            <flat-pickr id="inputPoCreated" name="po_created" v-bind:config="defaultFlatPickrConfig" v-model="po.po_created" class="form-control" v-validate="'required'" data-vv-as="{{ trans('purchase_order.fields.po_created') }}"></flat-pickr>
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
                                    <h3 class="block-title">@lang('purchase_order.index.panel.shipping_panel.title')</h3>
                                    <div class="block-options">
                                        <button type="button" class="btn-block-option" data-toggle="block-option" data-action="content_toggle"></button>
                                    </div>
                                </div>
                                <div class="block-content">
                                    <div v-bind:class="{ 'form-group row':true, 'is-invalid':errors.has('shipping_date') }">
                                        <label for="inputShippingDate" class="col-3 col-form-label">@lang('purchase_order.fields.shipping_date')</label>
                                        <div class="col-md-9">
                                            <flat-pickr id="inputShippingDate" v-model="po.shipping_date" name="shipping_date" v-bind:config="defaultFlatPickrConfig" v-model="po.po_created" class="form-control" v-validate="'required'" data-vv-as="{{ trans('purchase_order.fields.shipping_date') }}"></flat-pickr>
                                        </div>
                                        <span v-show="errors.has('shipping_date')" class="invalid-feedback">@{{ errors.first('shipping_date') }}</span>
                                    </div>
                                    <div v-bind:class="{ 'form-group row':true, 'is-invalid':errors.has('warehouse_id') }">
                                        <label for="inputWarehouse" class="col-3 col-form-label">@lang('purchase_order.fields.warehouse')</label>
                                        <div class="col-sm-9">
                                            <select id="inputWarehouse" name="warehouse_id" class="form-control"
                                                    v-model="po.warehouseHId"
                                                    v-validate="'required'"
                                                    data-vv-as="{{ trans('purchase_order.fields.warehouse') }}">
                                                <option v-bind:value="defaultPleaseSelect">@lang('labels.PLEASE_SELECT')</option>
                                                <option v-for="(warehouse, warehouseIdx) of warehouseDDL" v-bind:value="warehouse.hId">@{{ warehouse.name }} @{{ warehouse.remarks ? '('+warehouse.remarks+')':'' }}</option>
                                            </select>
                                            <span v-show="errors.has('warehouse_id')" class="invalid-feedback">@{{ errors.first('warehouse_id') }}</span>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="form-group row">
                                        <label for="inputVendorTrucking" class="col-3 col-form-label">@lang('purchase_order.fields.vendor_trucking')</label>
                                        <div class="col-md-9">
                                            <select id="inputVendorTrucking" name="vendor_trucking_id" class="form-control"
                                                    v-model="po.vendorTruckingHId">
                                                <option v-bind:value="defaultPleaseSelect">@lang('labels.PLEASE_SELECT')</option>
                                                <option v-for="(vendorTrucking, vendorTruckingIdx) of vendorTruckingDDL" v-bind:value="vendorTrucking.hId">@{{ vendorTrucking.name }}</option>
                                            </select>
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
                                    <div class="row">
                                        <div class="col-11">
                                            <select class="form-control" v-model="productSelected" v-on:change="onChangeProductSelected(productSelected)">
                                                <option v-bind:value="defaultPleaseSelect">@lang('labels.PLEASE_SELECT')</option>
                                                <option v-for="(p, pIdx) in product_options" v-bind:value="p.hId">@{{ p.name }}</option>
                                            </select>
                                        </div>
                                        <div class="col-1">
                                            <button id="supplierDetailButton" type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#supplierDetailModal" v-on:click="insertItem(productSelected)">
                                                <span class="fa fa-plus fa-fw"></span>
                                            </button>
                                        </div>
                                    </div>
                                    <br/>
                                    <div class="table-responsive">
                                        <table id="itemsListTable" class="table table-bordered table-striped table-vcenter">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th colspan="8">@lang('purchase_order.index.table.item_table.header.title')</th>
                                                </tr>
                                                <tr>
                                                    <th>@lang('purchase_order.index.table.item_table.header.product_name')</th>
                                                    <th class="text-center">@lang('purchase_order.index.table.item_table.header.quantity')</th>
                                                    <th class="text-center">@lang('purchase_order.index.table.item_table.header.unit')</th>
                                                    <th class="text-center">@lang('purchase_order.index.table.item_table.header.price_unit')</th>
                                                    <th class="text-center" colspan="2">@lang('purchase_order.index.table.item_table.header.discount')</th>
                                                    <th>&nbsp;</th>
                                                    <th class="text-center">@lang('purchase_order.index.table.item_table.header.total_price')</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-if="po.items.length == 0">
                                                    <td colspan="8" class="text-center">@lang('labels.DATA_NOT_FOUND')</td>
                                                </tr>
                                                <tr v-for="(i, iIdx) in po.items">
                                                    <td>
                                                        @{{ i.product.name }}
                                                        <input type="hidden" name="item_product_id[]" v-model="i.product.hId"/>
                                                    </td>
                                                    <td width="5%">
                                                        <vue-autonumeric type="text" name="item_quantity[]"
                                                                 v-bind:class="{ 'form-control text-align-right':true, 'is-invalid':errors.has('quantity_' + iIdx) }"
                                                                 v-bind:options="defaultNumericConfig"
                                                                 v-bind:data-vv-name="'quantity_' + iIdx"
                                                                 v-bind:data-vv-as="'{{ trans('purchase_order.index.table.item_table.header.quantity') }} ' + (iIdx + 1)"
                                                                 v-model="i.quantity" v-validate="'required'"></vue-autonumeric>
                                                    </td>
                                                    <td width="15%">
                                                        <select v-bind:class="{ 'form-control':true, 'is-invalid':errors.has('product_unit_' + iIdx) }"
                                                                name="item_selected_product_unit_id[]"
                                                                v-model="i.selected_product_unit.hId"
                                                                v-bind:data-vv-name="'product_unit_' + iIdx"
                                                                v-bind:data-vv-as="'{{ trans('purchase_order.index.table.item_table.header.unit') }} ' + (iIdx + 1)"
                                                                v-validate="'required'"
                                                                v-on:change="onChangeProductUnit(iIdx)">
                                                            <option v-bind:value="defaultPleaseSelect">@lang('labels.PLEASE_SELECT')</option>
                                                            <option v-for="(pu, puIdx) in i.product.product_units" v-bind:value="pu.hId">@{{ pu.unit.unitName }}</option>
                                                        </select>
                                                        <input type="hidden" name="conversion_value[]" v-model="i.conversion_value">
                                                        <input type="hidden" name="base_product_unit_id[]" v-model="i.base_product_unit.hId">
                                                    </td>
                                                    <td width="13%">
                                                        <vue-autonumeric type="text" name="item_price[]"
                                                                         v-bind:class="{ 'form-control text-align-right':true, 'is-invalid':errors.has('price_' + iIdx) }"
                                                                         v-model="i.price" v-validate="'required'"
                                                                         v-bind:options="defaultCurrencyConfig"
                                                                         v-bind:data-vv-name="'price_' + iIdx"
                                                                         v-bind:data-vv-as="'{{ trans('purchase_order.index.table.item_table.header.price_unit') }} ' + (iIdx + 1)"></vue-autonumeric>
                                                    </td>
                                                    <td width="7%">
                                                        <vue-autonumeric type="text" class="form-control text-align-right" v-model="i.discount_pct" v-bind:options="defaultPercentageConfig" placeholder="0%" v-on:input="setDiscountValue(iIdx)"></vue-autonumeric>
                                                    </td>
                                                    <td width="10%">
                                                        <vue-autonumeric type="text" name="discount[]" class="form-control text-align-right" v-model="i.discount" v-bind:options="defaultCurrencyConfig" v-on:input="setDiscountPct(iIdx)" placeholder="0"></vue-autonumeric>
                                                    </td>
                                                    <td width="3%">
                                                        <button type="button" class="btn btn-danger btn-md" v-on:click="removeItem(itemIndex)"><span class="fa fa-minus"></span></button>
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
                                                    <th colspan="5">@lang('purchase_order.index.table.expense_table.header.title')</th>
                                                    <th class="text-align-right">
                                                        <button type="button" class="btn-block-option"
                                                                data-toggle="tooltip" title="{{ trans('buttons.create_new_button') }}"
                                                                v-on:click="addExpense">
                                                            <i class="si si-plus"></i>
                                                        </button>
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <th>@lang('purchase_order.index.table.expense_table.header.name')</th>
                                                    <th width="15%"
                                                        class="text-center">@lang('purchase_order.index.table.expense_table.header.type')</th>
                                                    <th width="10%"
                                                        class="text-center">@lang('purchase_order.index.table.expense_table.header.internal_expense')</th>
                                                    <th width="30%"
                                                        class="text-center">@lang('purchase_order.index.table.expense_table.header.remarks')</th>
                                                    <th width="3%">&nbsp;</th>
                                                    <th width="12%"
                                                        class="text-center">@lang('purchase_order.index.table.expense_table.header.amount')</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-if="po.expenses.length == 0">
                                                    <td colspan="6" class="text-center">@lang('labels.DATA_NOT_FOUND')</td>
                                                </tr>
                                                <tr v-for="(expense, expenseIndex) in po.expenses">
                                                    <td v-bind:class="{ 'is-invalid':errors.has('expense_name_' + expenseIndex) }">
                                                        <input name="expense_name[]" type="text" class="form-control"
                                                               v-model="expense.name" v-validate="'required'" v-bind:data-vv-as="'{{ trans('purchase_order.index.table.expense_table.header.name') }} ' + (expenseIndex + 1)"
                                                               v-bind:data-vv-name="'expense_name_' + expenseIndex">
                                                    </td>
                                                    <td v-bind:class="{ 'is-invalid':errors.has('expense_type_' + expenseIndex) }">
                                                        <select class="form-control" v-model="expense.type.code" name="expense_type[]"
                                                                v-validate="'required'" v-bind:data-vv-as="'{{ trans('purchase_order.index.table.expense_table.header.type') }} ' + (expenseIndex + 1)"
                                                                v-bind:data-vv-name="'expense_type_' + expenseIndex">
                                                            <option v-bind:value="defaultPleaseSelect">@lang('labels.PLEASE_SELECT')</option>
                                                            <option v-for="(expenseType, expenseTypeIdx) in expenseTypeDDL" v-bind:value="expenseType.code">@{{ expenseType.description }}</option>
                                                        </select>
                                                    </td>
                                                    <td class="text-center">
                                                        <input type="checkbox" v-model="expense.is_internal_expense">
                                                        <input type="hidden" name="is_internal_expense" v-model="expense.is_internal_expense_val">
                                                    </td>
                                                    <td>
                                                        <input name="expense_remarks[]" type="text" class="form-control" v-model="expense.remarks"/>
                                                    </td>
                                                    <td class="text-center">
                                                        <button type="button" class="btn btn-danger btn-md" v-on:click="removeExpense(expenseIndex)">
                                                            <span class="fa fa-minus"></span>
                                                        </button>
                                                    </td>
                                                    <td v-bind:class="{ 'is-invalid':errors.has('expense_amount_' + expenseIndex) }">
                                                        <vue-autonumeric name="expense_amount[]" type="text" class="form-control text-align-right"
                                                                         v-model="expense.amount" v-validate="'required'"
                                                                         v-bind:options="defaultCurrencyConfig"
                                                                         v-bind:data-vv-as="'{{ trans('purchase_order.index.table.expense_table.header.amount') }} ' + (expenseIndex + 1)"
                                                                         v-bind:data-vv-name="'expense_amount_' + expenseIndex"><</vue-autonumeric>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="table-responsive">
                                        <table id="itemsTotalListTable" class="table table-bordered table-vcenter">
                                            <tbody>
                                                <tr>
                                                    <td colspan="7" class="text-align-right">@lang('purchase_order.index.table.total_table.header.subtotal')</td>
                                                    <td width="12%" class="text-align-right">
                                                        <vue-autonumeric v-bind:tag="'span'" v-bind:options="currencyFormatToString" v-model="po.subtotal"></vue-autonumeric>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="7" class="text-align-right">@lang('purchase_order.index.table.total_table.header.disc_total_pct')</td>
                                                    <td width="12%">
                                                        <vue-autonumeric type="text" class="form-control text-align-right" v-model="po.disc_total_percent" v-bind:options="defaultPercentageConfig" placeholder="0%"></vue-autonumeric>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="7" class="text-align-right">@lang('purchase_order.index.table.total_table.header.disc_total_value')</td>
                                                    <td width="12%">
                                                        <vue-autonumeric type="text" class="form-control text-align-right" v-model="po.disc_total_value" v-bind:options="defaultCurrencyConfig"></vue-autonumeric>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="7" class="text-align-right">@lang('purchase_order.index.table.total_table.header.grandtotal')</td>
                                                    <td width="12%" class="text-align-right">
                                                        <vue-autonumeric v-bind:tag="'span'" v-bind:options="currencyFormatToString" v-model="po.grandtotal"></vue-autonumeric>
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
                                    <h3 class="block-title">@lang('purchase_order.index.panel.remarks_panel.title')</h3>
                                    <div class="block-options">
                                        <button type="button" class="btn-block-option" data-toggle="block-option" data-action="content_toggle"></button>
                                    </div>
                                </div>
                                <div class="block-content">
                                    <div>
                                        <ul class="nav nav-tabs nav-tabs-alt" data-toggle="tabs" role="tablist">
                                            <li class="nav-item">
                                                <a class="nav-link active" href="#tabs_remarks">@lang('purchase_order.index.tabs.remarks')</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" href="#tabs_internal">@lang('purchase_order.index.tabs.internal')</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" href="#tabs_private">@lang('purchase_order.index.tabs.private')</a>
                                            </li>
                                        </ul>
                                        <div class="block-content tab-content overflow-hidden">
                                            <div class="tab-pane fade fade-up show active" id="tabs_remarks" role="tabpanel">
                                                <textarea id="inputRemarks" name="remarks" class="form-control" rows="5" v-model="po.remarks"></textarea>
                                            </div>
                                            <div class="tab-pane fade fade-up show" id="tabs_internal" role="tabpanel">
                                                <textarea id="inputInternalRemarks" name="internal_remarks" class="form-control" rows="5" v-model="po.internal_remarks"></textarea>
                                            </div>
                                            <div class="tab-pane fade fade-up show" id="tabs_private" role="tabpanel">
                                                <textarea id="inputPrivateRemarks" name="private_remarks" class="form-control" rows="5" v-model="po.private_remarks"></textarea>
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
                vendorTruckingDDL: [],
                expenseTypeDDL: [],
                warehouseDDL: [],
                selectedDate: new Date(),
                selectedSupplier: {},
                poStatusDesc: '',
                statusDDL: [],
                product_options: [],
                productSelected: '',
                isFinishLoadingMounted: false,
                allProduct: [],
                mode: '',
                po: {
                    items:[],
                    expenses: [],
                    disc_total_percent: 0,
                    disc_total_value: 0,
                    subtotal: 0,
                    grandtotal: 0
                }
            },
            mounted: function () {
                this.mode = 'list';
                this.getAllPO();

                Promise.all([
                    this.getSupplier(),
                    this.getSupplierType(),
                    this.getPOType(),
                    this.getWarehouse(),
                    this.getVendorTrucking(),
                    this.getAllProduct(),
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
                        Codebase.blocks('#poCRUDBlock', 'state_toggle');
                        if (this.mode == 'create') {
                            axios.post(route('api.post.po.save'), new FormData($('#poForm')[0])).then(response => {
                                this.backToList();
                                Codebase.blocks('#poCRUDBlock', 'state_toggle');
                            }).catch(e => {
                                this.handleErrors(e);
                                Codebase.blocks('#poCRUDBlock', 'state_toggle');
                            });
                        } else if (this.mode == 'edit') {
                            axios.post(route('api.post.po.edit.', this.po.hId), new FormData($('#poForm')[0])).then(response => {
                                this.backToList();
                                Codebase.blocks('#poCRUDBlock', 'state_toggle');
                            }).catch(e => {
                                this.handleErrors(e);
                                Codebase.blocks('#poCRUDBlock', 'state_toggle');
                            });
                        } else { }
                    });
                },
                getAllPO: function (date) {
                    Codebase.blocks('#poListBlock', 'state_toggle');

                    var qS = [];
                    if (date && typeof(date) == 'string') {
                        qS.push({'key': 'date', 'value': date});
                    }

                    axios.get(route('api.get.po.read').url()).then(response => {
                        this.poList = response.data;
                        Codebase.blocks('#poListBlock', 'state_toggle');
                    }).catch(e => {
                        this.handleErrors(e);
                    });
                },
                onChangeSupplierType: function (type) {
                    if (type == 'SUPPLIERTYPE.WI') {
                        this.po.supplierHId = '';
                    }
                },
                createNew: function () {
                    this.mode = 'create';
                    this.errors.clear();
                    this.po = this.emptyPO();
                },
                editSelected: function (idx) {
                    this.mode = 'edit';
                    this.errors.clear();
                    this.po = this.poList[idx];
                },
                showSelected: function (idx) {
                    this.mode = 'show';
                    this.errors.clear();
                    this.po = this.poList[idx];
                },
                deleteSelected: function (idx) {
                    axios.post('/api/post/po/delete/' + idx).then(response => {
                        this.backToList();
                    }).catch(e => {
                        this.handleErrors(e);
                    });
                },
                backToList: function () {
                    this.mode = 'list';
                    this.errors.clear();
                    this.getAllPO();
                },
                emptyPO: function () {
                    return {
                        hId: '',
                        po_code: this.generatePOCode(),
                        po_created: new Date(),
                        shipping_date: new Date(),
                        supplier_type: '',
                        supplierHId: '',
                        warehouseHId: '',
                        vendorTruckingHId: '',
                        po_type: '',
                        po_status: 'POSTATUS.D',
                        productHId: '',
                        items: [],
                        expenses: [],
                        disc_total_percent: 0,
                        disc_total_value: 0,
                        subtotal: 0,
                        grandtotal: 0
                    }
                },
                onChangeProductSelected(productId) {
                    this.insertItem(productId);
                },
                insertItem: function (productId) {
                    if(productId != ''){
                        let prd = _.cloneDeep(_.find(this.product_options, { hId: productId }));
                        this.po.items.push({
                            product: prd,
                            selected_product_unit: this.defaultProductUnit(),
                            base_product_unit: _.cloneDeep(_.find(prd.product_units, {is_base: 1})),
                            quantity: 0,
                            price: 0,
                            discount_pct: 0,
                            discount: 0,
                            total: 0
                        });
                    }
                },
                removeItem: function (index) {
                    this.po.items.splice(index, 1);
                },
                addExpense: function () {
                    this.po.expenses.push({
                        name: '',
                        type: {
                            code: ''
                        },
                        is_internal_expense: false,
                        is_internal_expense_val: 0,
                        amount: 0,
                        remarks: ''
                    });
                },
                removeExpense: function (index) {
                    this.po.expenses.splice(index, 1);
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
                    if (this.po.items[itemIndex].selected_product_unit.hId == '') {
                        this.po.items[itemIndex].selected_product_unit = this.defaultProductUnit();
                    } else {
                        var pUnit = _.find(this.po.items[itemIndex].product.product_units, { hId: this.po.items[itemIndex].selected_product_unit.hId });
                        _.merge(this.po.items[itemIndex].selected_product_unit, pUnit);
                    }
                },
                getSupplier: function() {
                    return new Promise((resolve, reject) => {
                        axios.get(route('api.get.supplier.read').url() + this.generateQueryStrings([{'key':'all', 'value':'yes'}])).then(
                            response => {
                                this.supplierDDL = response.data;
                                resolve(true);
                            }
                        ).catch(e => {
                            this.handleErrors(e);
                            reject(e.response.data.message);
                        });
                    });
                },
                setDiscountValue: function(index) {
                    if (typeof(index) != 'number') {
                        this.po.disc_total_value = (this.po.disc_total_percent / 100) * this.po.subtotal;
                    } else {
                        this.po.items[index].discount =
                            (this.po.items[index].discount_pct / 100) *
                            (this.po.items[index].selected_product_unit.conversion_value * this.po.items[index].quantity * this.po.items[index].price);
                    }
                },
                setDiscountPct: function(index) {
                    if (typeof(index) != 'number') {
                        this.po.disc_total_percent = (this.po.disc_total_value / this.po.subtotal) * 100;
                    } else {
                        this.po.items[index].discount_pct =
                            (this.po.items[index].discount /
                            (this.po.items[index].selected_product_unit.conversion_value * this.po.items[index].quantity * this.po.items[index].price)) * 100;
                    }
                },
                getSupplierType: function() {
                    return new Promise((resolve, reject) => {
                        axios.get(route('api.get.lookup.bycategory', 'SUPPLIER_TYPE').url()).then(
                            response => {
                                this.supplierTypeDDL = response.data;
                                resolve(true);
                            }
                        ).catch(e => {
                            this.handleErrors(e);
                            reject(e.response.data.message);
                        });
                    });
                },
                getPOType: function() {
                    return new Promise((resolve, reject) => {
                        axios.get(route('api.get.lookup.bycategory', 'PO_TYPE').url()).then(
                            response => {
                                this.poTypeDDL = response.data;
                                resolve(true);
                            }
                        ).catch(e => {
                            this.handleErrors(e);
                            reject(e.response.data.message);
                        });
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
                getAllProduct: function() {
                    return new Promise((resolve, reject) => {
                        axios.get(route('api.get.product.readall').url()).then(response => {
                            this.allProduct = response.data;
                            resolve(true);
                        }).catch(e => {
                            this.handleErrors(e);
                            reject(e.response.data.message);
                        });
                    });
                },
                generatePOCode: function() {
                    axios.get(route('api.get.po.generate.po_code').url()).then(
                        response => { this.po.po_code = response.data; }
                    );
                },
                getExpenseType: function() {
                    axios.get(route('api.get.lookup.bycategory', 'EXPENSE_TYPE').url()).then(
                        response => { this.expenseTypeDDL = response.data; }
                    );
                },
                calculateTotal: function() {
                    var allItemTotal = 0;
                    _.forEach(this.po.items, function(item, key) {
                        var itemTotal = 0;
                        itemTotal = (item.selected_product_unit.conversion_value * item.quantity * item.price) - item.discount;
                        item.total = itemTotal;

                        allItemTotal += itemTotal;
                    });

                    var expenseTotal = 0;
                    _.forEach(this.po.expenses, function (expense, key) {
                        if (expense.type.code === 'EXPENSETYPE.ADD')
                            expenseTotal += expense.amount;
                        else
                            expenseTotal -= expense.amount;
                    });

                    this.po.subtotal = allItemTotal + expenseTotal;
                    this.po.grandtotal = this.po.subtotal - this.po.disc_total_value;
                },
            },
            watch: {
                'po.supplierHId': function() {
                    if (this.po.supplierHId == '') {
                        this.selectedSupplier = {};
                        this.product_options = [];
                    } else {
                        this.selectedSupplier = _.find(this.supplierDDL, { hId: this.po.supplierHId });
                        this.product_options = this.selectedSupplier.products;
                    }
                },
                'po.supplier_type': function() {
                    if (this.po.supplier_type == 'SUPPLIERTYPE.WI') {
                        this.product_options = this.allProduct;
                    }
                },
                'po.po_status': function() {
                    if (this.po.po_status != '') {
                        axios.get(route('api.get.lookup.description.byvalue', 'POSTATUS.D').url()).then(
                            response => { this.poStatusDesc = response.data; }
                        );
                    }
                },
                'po.items': {
                    deep: true,
                    handler: function(oldVal, newVal) {
                        this.calculateTotal();
                    }
                },
                'po.expenses': {
                    deep: true,
                    handler: function(oldVal, newVal) {
                        this.calculateTotal();
                    }
                },
                'po.disc_total_percent': function() {
                    this.setDiscountValue();
                    this.calculateTotal();
                },
                'po.disc_total_value': function() {
                    this.setDiscountPct();
                    this.calculateTotal();
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
                flatPickrInlineConfig: function() {
                    var conf = Object.assign({}, this.defaultFlatPickrConfig);

                    conf.inline = true;
                    conf.altInput = true;
                    conf.altInputClass = 'hideTextBox';
                    conf.enableTime = false;
                    conf.enable = [
                        function(date) {
                            return (date.getMonth() % 2 === 0 && date.getDate() < 15);
                        }
                    ];

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
    <script type="application/javascript" src="{{ mix('js/apps/po.js') }}"></script>
@endsection
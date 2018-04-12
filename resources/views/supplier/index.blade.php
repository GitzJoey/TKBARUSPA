@extends('layouts.codebase.master')

@section('title')
    @lang('supplier.index.title')
@endsection

@section('page_title')
    <span class="fa fa-building-o fa-fw"></span>&nbsp;
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
                        <template v-if="supplierList.hasOwnProperty('data') && supplierList.data.length != 0">
                            <tr v-for="(s, sIdx) in supplierList.data">
                                <td>@{{ s.name }}</td>
                                <td>@{{ s.address }}</td>
                                <td>@{{ s.tax_id }}</td>
                                <td>@{{ s.statusI18n }}</td>
                                <td>@{{ s.remarks }}</td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <button class="btn btn-sm btn-secondary" v-on:click="showSelected(sIdx)"><span class="fa fa-info fa-fw"></span></button>
                                        <button class="btn btn-sm btn-secondary" v-on:click="editSelected(sIdx)"><span class="fa fa-pencil fa-fw"></span></button>
                                        <button class="btn btn-sm btn-secondary" v-on:click="deleteSelected(s.hId)"><span class="fa fa-close fa-fw"></span></button>
                                    </div>
                                </td>
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
                            <a class="nav-link" href="#tabs_bankaccounts">
                                @lang('supplier.index.tabs.bank_accounts')
                                <template v-if="errors.any('tabs_bankaccounts')">
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
                            <div v-bind:class="{ 'form-group row':true, 'is-invalid':errors.has('tabs_supplier.name') }">
                                <label for="inputName" class="col-2 col-form-label">@lang('supplier.fields.name')</label>
                                <div class="col-md-10">
                                    <template v-if="mode == 'create' || mode == 'edit'">
                                        <input id="inputName" name="name" type="text" class="form-control" placeholder="@lang('supplier.fields.name')"
                                               v-model="supplier.name"
                                               v-validate="'required'" data-vv-as="{{ trans('supplier.fields.name') }}" data-vv-scope="tabs_supplier">
                                        <span v-show="errors.has('tabs_supplier.name')" class="invalid-feedback">@{{ errors.first('tabs_supplier.name') }}</span>
                                    </template>
                                    <template v-if="mode == 'show'">
                                        <div class="form-control-plaintext">@{{ supplier.name }}</div>
                                    </template>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="inputCodeSign" class="col-2 col-form-label">@lang('supplier.fields.code_sign')</label>
                                <div class="col-md-10">
                                    <template v-if="mode == 'create' || mode == 'edit'">
                                        <input id="inputCodeSign" name="code_sign" v-model="supplier.code_sign" type="text" class="form-control" placeholder="@lang('supplier.fields.code_sign')">
                                    </template>
                                    <template v-if="mode == 'show'">
                                        <div class="form-control-plaintext">@{{ supplier.code_sign }}</div>
                                    </template>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="inputAddress" class="col-2 col-form-label">@lang('supplier.fields.address')</label>
                                <div class="col-md-10">
                                    <template v-if="mode == 'create' || mode == 'edit'">
                                        <textarea name="address" v-model="supplier.address" id="inputAddress" class="form-control" rows="4"></textarea>
                                    </template>
                                    <template v-if="mode == 'show'">
                                        <div class="form-control-plaintext">@{{ supplier.address }}</div>
                                    </template>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="inputCity" class="col-2 col-form-label">@lang('supplier.fields.city')</label>
                                <div class="col-md-10">
                                    <template v-if="mode == 'create' || mode == 'edit'">
                                        <input id="inputCity" name="city" v-model="supplier.city" type="text" class="form-control" placeholder="@lang('supplier.fields.city')">
                                    </template>
                                    <template v-if="mode == 'show'">
                                        <div class="form-control-plaintext">@{{ supplier.city }}</div>
                                    </template>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="inputPhone" class="col-2 col-form-label">@lang('supplier.fields.phone')</label>
                                <div class="col-md-10">
                                    <template v-if="mode == 'create' || mode == 'edit'">
                                        <input id="inputPhone" name="phone_number" type="text" v-model="supplier.phone_number" class="form-control" placeholder="@lang('supplier.fields.phone')">
                                    </template>
                                    <template v-if="mode == 'show'">
                                        <div class="form-control-plaintext">@{{ supplier.phone_number }}</div>
                                    </template>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="inputFax" class="col-2 col-form-label">@lang('supplier.fields.fax_num')</label>
                                <div class="col-md-10">
                                    <template v-if="mode == 'create' || mode == 'edit'">
                                        <input id="inputFax" name="fax_num" type="text" v-model="supplier.fax_num" class="form-control" placeholder="@lang('supplier.fields.fax_num')">
                                    </template>
                                    <template v-if="mode == 'show'">
                                        <div class="form-control-plaintext">@{{ supplier.fax_num }}</div>
                                    </template>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="inputTaxId" class="col-2 col-form-label">@lang('supplier.fields.tax_id')</label>
                                <div class="col-md-10">
                                    <template v-if="mode == 'create' || mode == 'edit'">
                                        <input id="inputTaxId" name="tax_id" type="text" v-model="supplier.tax_id" class="form-control" placeholder="@lang('supplier.fields.tax_id')">
                                    </template>
                                    <template v-if="mode == 'show'">
                                        <div class="form-control-plaintext">@{{ supplier.tax_id }}</div>
                                    </template>
                                </div>
                            </div>
                            <div v-bind:class="{ 'form-group row':true, 'is-invalid':errors.has('tabs_supplier.status') }">
                                <label for="inputStatus" class="col-2 col-form-label">@lang('supplier.fields.status')</label>
                                <div class="col-md-10">
                                    <template v-if="mode == 'create' || mode == 'edit'">
                                        <select id="inputStatus"
                                                class="form-control"
                                                name="status"
                                                v-model="supplier.status"
                                                v-validate="'required'"
                                                data-vv-as="{{ trans('supplier.fields.status') }}"
                                                data-vv-scope="tabs_supplier">
                                            <option v-bind:value="defaultPleaseSelect">@lang('labels.PLEASE_SELECT')</option>
                                            <option v-for="(s, sIdx) in statusDDL" v-bind:value="s.code">@{{ s.description }}</option>
                                        </select>
                                        <span v-show="errors.has('tabs_supplier.status')" class="invalid-feedback">@{{ errors.first('tabs_supplier.status') }}</span>
                                    </template>
                                    <template v-if="mode == 'show'">
                                        <div class="form-control-plaintext">@{{ supplier.statusI18n }}</div>
                                    </template>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="inputRemarks" class="col-2 col-form-label">@lang('supplier.fields.remarks')</label>
                                <div class="col-md-10">
                                    <template v-if="mode == 'create' || mode == 'edit'">
                                        <input id="inputRemarks" name="remarks" v-model="supplier.remarks" type="text" class="form-control" placeholder="@lang('supplier.fields.remarks')">
                                    </template>
                                    <template v-if="mode == 'show'">
                                        <div class="form-control-plaintext">@{{ supplier.remarks }}</div>
                                    </template>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade fade-up show" id="tabs_pic" role="tabpanel">
                            <div class="row">
                                <div class="col-2">
                                    <template v-if="mode == 'create' || mode == 'edit'">
                                        <button type="button" class="btn btn-xs btn-default" v-on:click="addNewPIC">@lang('buttons.create_new_button')</button>
                                    </template>
                                    <template v-if="mode == 'show'">
                                    </template>
                                </div>
                                <div class="col-10">
                                    <div v-for="(p, pIdx) in supplier.persons_in_charge">
                                        <div class="block block-shadow-on-hover block-mode-loading-refresh">
                                            <div class="block-header block-header-default">
                                                <h3 class="block-title">@lang('supplier.index.panel.pic.title')&nbsp;@{{ pIdx + 1 }}</h3>
                                                <div class="block-options">
                                                    <template v-if="mode == 'create' || mode == 'edit'">
                                                        <button type="button" class="btn btn-sm btn-danger" v-on:click="removeSelectedPIC(pIdx)">@lang('buttons.remove_button')</button>
                                                    </template>
                                                    <template v-if="mode == 'show'">
                                                    </template>
                                                </div>
                                            </div>
                                            <div class="block-content">
                                                <div v-bind:class="{ 'form-group row':true, 'is-invalid':errors.has('tabs_pic.first_name_' + pIdx) }">
                                                    <label for="inputFirstName" class="col-2 col-form-label">@lang('supplier.fields.first_name')</label>
                                                    <div class="col-md-10">
                                                        <template v-if="mode == 'create' || mode == 'edit'">
                                                            <input type="hidden" name="profile_id[]" v-model="p.hId"/>
                                                            <input id="inputFirstName" type="text" name="first_name[]" class="form-control" v-model="p.first_name" placeholder="@lang('supplier.fields.first_name')"
                                                                   v-validate="'required'" v-bind:data-vv-as="'{{ trans('supplier.fields.first_name') }} ' + (pIdx + 1)" v-bind:data-vv-name="'first_name_' + pIdx"
                                                                   data-vv-scope="tabs_pic">
                                                            <span v-show="errors.has('tabs_pic.first_name_' + pIdx)" class="invalid-feedback">@{{ errors.first('tabs_pic.first_name_' + pIdx) }}</span>
                                                        </template>
                                                        <template v-if="mode == 'show'">
                                                            <div class="form-control-plaintext">@{{ p.first_name }}</div>
                                                        </template>
                                                    </div>
                                                </div>
                                                <div v-bind:class="{ 'form-group row':true, 'is-invalid':errors.has('tabs_pic.last_name_' + pIdx) }">
                                                    <label for="inputLastName" class="col-2 col-form-label">@lang('supplier.fields.last_name')</label>
                                                    <div class="col-md-10">
                                                        <template v-if="mode == 'create' || mode == 'edit'">
                                                            <input id="inputLastName" type="text" name="last_name[]" class="form-control" v-model="p.last_name" placeholder="@lang('supplier.fields.last_name')"
                                                                   v-validate="'required'" v-bind:data-vv-as="'{{ trans('supplier.fields.last_name') }} ' + (pIdx + 1)" v-bind:data-vv-name="'last_name_' + pIdx"
                                                                   data-vv-scope="tabs_pic">
                                                            <span v-show="errors.has('tabs_pic.last_name_' + pIdx)" class="invalid-feedback">@{{ errors.first('tabs_pic.last_name_' + pIdx) }}</span>
                                                        </template>
                                                        <template v-if="mode == 'show'">
                                                            <div class="form-control-plaintext">@{{ p.last_name }}</div>
                                                        </template>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="inputAddress" class="col-2 col-form-label">@lang('supplier.fields.address')</label>
                                                    <div class="col-md-10">
                                                        <template v-if="mode == 'create' || mode == 'edit'">
                                                            <input id="inputAddress" type="text" name="profile_address[]" class="form-control" v-model="p.address" placeholder="@lang('supplier.fields.address')">
                                                        </template>
                                                        <template v-if="mode == 'show'">
                                                            <div class="form-control-plaintext">@{{ p.address }}</div>
                                                        </template>
                                                    </div>
                                                </div>
                                                <div v-bind:class="{ 'form-group row':true, 'is-invalid':errors.has('tabs_pic.ic_num_' + pIdx) }">
                                                    <label for="inputICNum" class="col-2 col-form-label">@lang('supplier.fields.ic_num')</label>
                                                    <div class="col-md-10">
                                                        <template v-if="mode == 'create' || mode == 'edit'">
                                                            <input id="inputICNum" type="text" name="ic_num[]" class="form-control" v-model="p.ic_num" placeholder="@lang('supplier.fields.ic_num')"
                                                                   v-validate="'required'" v-bind:data-vv-as="'{{ trans('supplier.fields.ic_num') }} ' + (pIdx + 1)" v-bind:data-vv-name="'ic_num_' + pIdx"
                                                                   data-vv-scope="tabs_pic">
                                                            <span v-show="errors.has('tabs_pic.ic_num_' + pIdx)" class="invalid-feedback">@{{ errors.first('tabs_pic.ic_num_' + pIdx) }}</span>
                                                        </template>
                                                        <template v-if="mode == 'show'">
                                                            <div class="form-control-plaintext">@{{ p.ic_num }}</div>
                                                        </template>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="inputPhoneNumber" class="col-2 col-form-label">@lang('supplier.fields.phone_number')</label>
                                                    <div class="col-md-10">
                                                        <table class="table table-bordered">
                                                            <thead class="thead-light">
                                                                <tr>
                                                                    <th>@lang('supplier.index.table.table_phone.header.provider')</th>
                                                                    <th>@lang('supplier.index.table.table_phone.header.number')</th>
                                                                    <th>@lang('supplier.index.table.table_phone.header.remarks')</th>
                                                                    <th class="text-center">@lang('labels.ACTION')</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr v-for="(ph, phIdx) in p.phone_numbers">
                                                                    <td v-bind:class="{ 'is-invalid':errors.has('tabs_pic.profile_' + pIdx + '_phoneprovider_' + phIdx) }">
                                                                        <input type="hidden" v-bind:name="'profile_' + pIdx + '_phone_numbers_id[]'" v-model="ph.hId"/>
                                                                        <template v-if="mode == 'create' || mode == 'edit'">
                                                                            <select v-bind:name="'profile_' + pIdx + '_phone_provider[]'" class="form-control" v-model="ph.phoneProviderHId"
                                                                                    v-validate="'required'" v-bind:data-vv-as="'{{ trans('supplier.index.table.table_phone.header.provider') }} ' + (phIdx + 1)"
                                                                                    v-bind:data-vv-name="'profile_' + pIdx + '_phoneprovider_' + phIdx" data-vv-scope="tabs_pic">
                                                                                <option v-bind:value="defaultPleaseSelect">@lang('labels.PLEASE_SELECT')</option>
                                                                                <option v-for="(p, pIdx) in providerDDL" v-bind:value="p.hId">@{{ p.name }} (@{{ p.short_name }})</option>
                                                                            </select>
                                                                        </template>
                                                                        <template v-if="mode == 'show'">
                                                                            <div class="form-control-plaintext">@{{ ph.provider.fullName }}</div>
                                                                        </template>
                                                                    </td>
                                                                    <td v-bind:class="{ 'is-invalid':errors.has('tabs_pic.profile_' + pIdx + '_number_' + phIdx) }">
                                                                        <template v-if="mode == 'create' || mode == 'edit'">
                                                                            <input type="text" v-bind:name="'profile_' + pIdx + '_phone_number[]'" class="form-control" v-model="ph.number"
                                                                                   v-validate="'required'" v-bind:data-vv-as="'{{ trans('supplier.index.table.table_phone.header.number') }} ' + (phIdx + 1)"
                                                                                   v-bind:data-vv-name="'profile_' + pIdx + '_number_' + phIdx" data-vv-scope="tabs_pic">
                                                                        </template>
                                                                        <template v-if="mode == 'show'">
                                                                            <div class="form-control-plaintext">@{{ ph.number }}</div>
                                                                        </template>
                                                                    </td>
                                                                    <td>
                                                                        <template v-if="mode == 'create' || mode == 'edit'">
                                                                            <input type="text" class="form-control" v-bind:name="'profile_' + pIdx +'_remarks[]'" v-model="ph.remarks">
                                                                        </template>
                                                                        <template v-if="mode == 'show'">
                                                                            <div class="form-control-plaintext">@{{ ph.remarks }}</div>
                                                                        </template>
                                                                    </td>
                                                                    <td class="text-center">
                                                                        <template v-if="mode == 'create' || mode == 'edit'">
                                                                            <button type="button" class="btn btn-xs btn-danger" v-bind:data="phIdx" v-on:click="removeSelectedPhone(pIdx, phIdx)">
                                                                                <span class="fa fa-close fa-fw"></span>
                                                                            </button>
                                                                        </template>
                                                                        <template v-if="mode == 'show'">
                                                                            <div class="form-control-plaintext">&nbsp;</div>
                                                                        </template>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                        <template v-if="mode == 'create' || mode == 'edit'">
                                                            <button type="button" class="btn btn-xs btn-default" v-on:click="addNewPhone(pIdx)">@lang('buttons.create_new_button')</button>
                                                        </template>
                                                        <template v-if="mode == 'show'">
                                                            <div class="form-control-plaintext">&nbsp;</div>
                                                        </template>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade fade-up show" id="tabs_bankaccounts" role="tabpanel">
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
                                        <td v-bind:class="{ 'is-invalid':errors.has('tabs_bankaccounts.bank_' + baIdx) }">
                                            <input type="hidden" name="bank_account_id[]" v-model="ba.hId"/>
                                            <template v-if="mode == 'create' || mode == 'edit'">
                                                <select class="form-control"
                                                        name="bank_id[]"
                                                        v-model="ba.bankHId"
                                                        v-validate="'required'"
                                                        v-bind:data-vv-as="'{{ trans('supplier.index.table.table_bank.header.bank') }} ' + (baIdx + 1)"
                                                        v-bind:data-vv-name="'bank_' + baIdx"
                                                        data-vv-scope="tabs_bankaccounts">
                                                    <option v-bind:value="defaultPleaseSelect">@lang('labels.PLEASE_SELECT')</option>
                                                    <option v-for="(b, bIdx) in bankDDL" v-bind:value="b.hId">@{{ b.bankFullName }}</option>
                                                </select>
                                            </template>
                                            <template v-if="mode == 'show'">
                                                <div class="form-control-plaintext">@{{ ba.bank.bankFullName }}</div>
                                            </template>
                                        </td>
                                        <td v-bind:class="{ 'is-invalid':errors.has('tabs_bankaccounts.account_name_' + baIdx) }">
                                            <template v-if="mode == 'create' || mode == 'edit'">
                                                <input type="text" class="form-control" name="account_name[]" v-model="ba.account_name"
                                                       v-validate="'required'"
                                                       v-bind:data-vv-as="'{{ trans('supplier.index.table.table_bank.header.account_name') }} ' + (baIdx + 1)"
                                                       v-bind:data-vv-name="'account_name_' + baIdx"
                                                       data-vv-scope="tabs_bankaccounts">
                                            </template>
                                            <template v-if="mode == 'show'">
                                                <div class="form-control-plaintext">@{{ ba.account_name }}</div>
                                            </template>
                                        </td>
                                        <td v-bind:class="{ 'is-invalid':errors.has('tabs_bankaccounts.account_number_' + baIdx) }">
                                            <template v-if="mode == 'create' || mode == 'edit'">
                                                <input type="text" class="form-control" name="account_number[]" v-model="ba.account_number"
                                                       v-validate="'required|numeric'" v-bind:data-vv-as="'{{ trans('supplier.index.table.table_bank.header.account_number') }} ' + (baIdx + 1)"
                                                       v-bind:data-vv-name="'account_number_' + baIdx"
                                                       data-vv-scope="tabs_bankaccounts">
                                            </template>
                                            <template v-if="mode == 'show'">
                                                <div class="form-control-plaintext">@{{ ba.account_number }}</div>
                                            </template>
                                        </td>
                                        <td>
                                            <template v-if="mode == 'create' || mode == 'edit'">
                                                <input type="text" class="form-control" name="bank_remarks[]" v-model="ba.remarks">
                                            </template>
                                            <template v-if="mode == 'show'">
                                                <div class="form-control-plaintext">@{{ ba.remarks }}</div>
                                            </template>
                                        </td>
                                        <td class="text-center">
                                            <template v-if="mode == 'create' || mode == 'edit'">
                                                <button type="button" class="btn btn-xs btn-danger" v-bind:data="baIdx" v-on:click="removeSelectedBank(baIdx)"><span class="fa fa-close fa-fw"></span></button>
                                            </template>
                                            <template v-if="mode == 'show'">
                                                <div class="form-control-plaintext">&nbsp;</div>
                                            </template>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <template v-if="mode == 'create' || mode == 'edit'">
                                <button class="btn btn-xs btn-default" type="button" v-on:click="addNewBankAccount">@lang('buttons.create_new_button')</button>
                            </template>
                            <template v-if="mode == 'show'">
                            </template>
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
                                    <tr v-for="(pL, pLIdx) in productList.data">
                                        <td class="text-center">
                                            <template v-if="mode == 'create' || mode == 'edit'">
                                                <input type="checkbox" v-model="pL.checked" v-on:change="syncToSupplierProd(pLIdx)"/>
                                            </template>
                                            <template v-if="mode == 'show'">
                                                <input type="checkbox" v-model="pL.checked" disabled/>
                                            </template>
                                        </td>
                                        <td>@{{ pL.product_type.name }}</td>
                                        <td>@{{ pL.name }}</td>
                                        <td>@{{ pL.short_code }}</td>
                                        <td>@{{ pL.description }}</td>
                                        <td>@{{ pL.remarks }}</td>
                                    </tr>
                                </tbody>
                            </table>
                            <input type="hidden" name="productSelected" v-model="supplier.listSelectedProductHId">
                            <div class="row">
                                <div class="col-12 pull-right">
                                    <pagination v-bind:data="productList" v-on:pagination-change-page="getProduct"></pagination>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade fade-up show" id="tabs_settings" role="tabpanel">
                            <div v-bind:class="{ 'form-group row':true, 'is-invalid':errors.has('tabs_settings.payment_due_day') }">
                                <label for="inputPaymentDueDay" class="col-2 col-form-label">@lang('supplier.fields.payment_due_day')</label>
                                <div class="col-md-10">
                                    <template v-if="mode == 'create' || mode == 'edit'">
                                        <input id="inputPaymentDueDay" name="payment_due_day" v-model="supplier.payment_due_day" type="text" class="form-control"  placeholder="{{ trans('supplier.fields.payment_due_day') }}"
                                               v-validate="'required|numeric|max_value:100'" data-vv-as="{{ trans('supplier.fields.payment_due_day') }}" data-vv-scope="tabs_settings">
                                        <span v-show="errors.has('tabs_settings.payment_due_day')" class="invalid-feedback">@{{ errors.first('payment_due_day') }}</span>
                                    </template>
                                    <template v-if="mode == 'show'">
                                        <div class="form-control-plaintext">@{{ supplier.payment_due_day }}</div>
                                    </template>
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
                productList: [],
                mode: '',
                search_supplier_query: '',
                active_page: 0
            },
            mounted: function () {
                this.mode = 'list';
                this.getLookupStatus();
                this.getBank();
                this.getPhoneProvider();
                this.getAllSupplier();
            },
            methods: {
                validateBeforeSubmit: function() {
                    this.$validator.validateScopes().then(isValid => {
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
                    this.getProduct();
                },
                editSelected: function(idx) {
                    this.mode = 'edit';
                    this.errors.clear();
                    this.supplier = this.supplierList.data[idx];
                    this.getProduct();
                },
                showSelected: function(idx) {
                    this.mode = 'show';
                    this.errors.clear();
                    this.supplier = this.supplierList.data[idx];
                    this.getProduct();
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
                        products: [],
                        listSelectedProductHId: []
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
                addNewPIC: function() {
                    this.supplier.persons_in_charge.push({
                        hId: '',
                        first_name: '',
                        last_name: '',
                        email: '',
                        address: '',
                        ic_num: '',
                        image_filename: '',
                        phone_numbers:[{
                            hId: '',
                            phoneProviderHId: '',
                            number: '',
                            remarks: ''
                        }]
                    });
                },
                removeSelectedPIC: function(idx) {
                    this.supplier.persons_in_charge.splice(idx, 1);
                },
                addNewPhone: function(parentIndex) {
                    if (!this.supplier.persons_in_charge[parentIndex].hasOwnProperty('phone_numbers')) {
                        this.supplier.persons_in_charge[parentIndex].phone_numbers = [];
                    }

                    this.supplier.persons_in_charge[parentIndex].phone_numbers.push({
                        hId: '',
                        phoneProviderHId: '',
                        number: '',
                        remarks: ''
                    });
                },
                removeSelectedPhone: function(parentIndex, idx) {
                    this.supplier.persons_in_charge[parentIndex].phone_numbers.splice(idx, 1);
                },
                getLookupStatus: function() {
                    axios.get('/api/get/lookup/byCategory/STATUS').then(
                        response => { this.statusDDL = response.data; }
                    );
                },
                getPhoneProvider: function() {
                    axios.get('/api/get/phone_provider/read').then(
                        response => { this.providerDDL = response.data; }
                    );
                },
                getBank: function() {
                    axios.get('/api/get/bank/read').then(
                        response => { this.bankDDL = response.data; }
                    );
                },
                getProduct: function(page) {
                    var qS = [];
                    if (page && typeof(page) == 'number') { qS.push({ 'key':'page', 'value':page }); }

                    axios.get('/api/get/product/read' + this.generateQueryStrings(qS)).then(
                        response => {
                            this.productList = response.data;

                            for (var i = 0; i < this.productList.data.length; i++) {
                                if (_.includes(this.supplier.listSelectedProductHId, this.productList.data[i].hId)) {
                                    this.productList.data[i].checked = true;
                                }
                            }
                        }
                    );
                },
                syncToSupplierProd: function(pLIdx) {
                    if (this.productList.data[pLIdx].checked) {
                        this.supplier.listSelectedProductHId.push(this.productList.data[pLIdx].hId);
                    } else {
                        _.pull(this.supplier.listSelectedProductHId, this.productList.data[pLIdx].hId);
                    }
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
@extends('layouts.codebase.master')

@section('title')
    @lang('customer.index.title')
@endsection

@section('page_title')
    <span class="fa fa-building-o fa-fw"></span>&nbsp;
    @lang('customer.index.page_title')
@endsection

@section('page_title_desc')
    @lang('customer.index.page_title_desc')
@endsection

@section('breadcrumbs')

@endsection

@section('content')
    <div id="customerVue">
        @include ('layouts.common.error')
        <div class="block block-shadow-on-hover block-mode-loading-refresh" id="customerListBlock">
            <div class="block-header block-header-default">
                <h3 class="block-title">@lang('customer.index.panel.list_panel.title')</h3>
                <div class="block-options">
                    <button type="button" class="btn-block-option" data-toggle="block-option" data-action="fullscreen_toggle"></button>
                    <button type="button" class="btn-block-option" v-on:click="getAllCustomer">
                        <i class="si si-refresh"></i>
                    </button>
                    <button type="button" class="btn-block-option" data-toggle="block-option" data-action="content_toggle"></button>
                </div>
            </div>
            <div class="block-content">
                <div class="row col-4">
                    <input type="text" class="form-control" id="inputSearchCustomer" placeholder="{{ trans('customer.fields.search_customer') }}"
                           v-model="search_customer_query" v-on:change="getAllCustomer"/>
                </div>
                <br>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-vcenter">
                        <thead class="thead-light">
                            <tr>
                                <th>@lang('customer.index.table.customer_list.header.name')</th>
                                <th>@lang('customer.index.table.customer_list.header.address')</th>
                                <th>@lang('customer.index.table.customer_list.header.tax_id')</th>
                                <th>@lang('customer.index.table.customer_list.header.status')</th>
                                <th>@lang('customer.index.table.customer_list.header.remarks')</th>
                                <th class="text-center action-column-width">@lang('labels.ACTION')</th>
                            </tr>
                        </thead>
                        <tbody>
                            <template v-if="customerList.hasOwnProperty('data') && customerList.data.length != 0">
                                <tr v-for="(s, sIdx) in customerList.data">
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
                            <pagination v-bind:data="customerList" v-on:pagination-change-page="getAllCustomer"></pagination>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="block block-shadow-on-hover block-mode-loading-refresh" id="customerCRUDBlock">
            <div class="block-header block-header-default">
                <h3 class="block-title">
                    <template v-if="mode == 'create'">@lang('customer.index.panel.crud_panel.title_create')</template>
                    <template v-if="mode == 'show'">@lang('customer.index.panel.crud_panel.title_show')</template>
                    <template v-if="mode == 'edit'">@lang('customer.index.panel.crud_panel.title_edit')</template>
                </h3>
                <div class="block-options">
                    <button type="button" class="btn-block-option" data-toggle="block-option" data-action="fullscreen_toggle"></button>
                    <button type="button" class="btn-block-option" data-toggle="block-option" data-action="content_toggle"></button>
                </div>
            </div>
            <div class="block-content">
                <form id="customerForm" method="post" v-on:submit.prevent="validateBeforeSubmit">
                    <ul class="nav nav-tabs nav-tabs-alt" data-toggle="tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" href="#tabs_customer">
                                @lang('customer.index.tabs.customer')
                                <template v-if="errors.any('tabs_customer')">
                                    &nbsp;<span id="customerDataTabError" class="red-asterisk"><i class="fa fa-close fa-fw"></i></span>
                                </template>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#tabs_pic">
                                @lang('customer.index.tabs.pic')
                                <template v-if="errors.any('tabs_pic')">
                                    &nbsp;<span id="picDataTabError" class="red-asterisk"><i class="fa fa-close fa-fw"></i></span>
                                </template>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#tabs_bankaccounts">
                                @lang('customer.index.tabs.bank_accounts')
                                <template v-if="errors.any('tabs_bankaccounts')">
                                    &nbsp;<span id="bankAccountTabError" class="red-asterisk"><i class="fa fa-close fa-fw"></i></span>
                                </template>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#tabs_settings">
                                @lang('customer.index.tabs.settings')
                                <template v-if="errors.any('tabs_settings')">
                                    &nbsp;<span id="settingsTabError" class="red-asterisk"><i class="fa fa-close fa-fw"></i></span>
                                </template>
                            </a>
                        </li>
                    </ul>
                    <div class="block-content tab-content overflow-hidden">
                        <div class="tab-pane fade fade-up show active" id="tabs_customer" role="tabpanel">
                            <div v-bind:class="{ 'form-group row':true, 'is-invalid':errors.has('tabs_customer.name') }">
                                <label for="inputName" class="col-2 col-form-label">@lang('customer.fields.name')</label>
                                <div class="col-md-10">
                                    <template v-if="mode == 'create' || mode == 'edit'">
                                        <input id="inputName" name="name" type="text" class="form-control" placeholder="@lang('customer.fields.name')"
                                               v-model="customer.name"
                                               v-validate="'required'" data-vv-as="{{ trans('customer.fields.name') }}" data-vv-scope="tabs_customer">
                                        <span v-show="errors.has('tabs_customer.name')" class="invalid-feedback">@{{ errors.first('tabs_customer.name') }}</span>
                                    </template>
                                    <template v-if="mode == 'show'">
                                        <div class="form-control-plaintext">@{{ customer.name }}</div>
                                    </template>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="inputCodeSign" class="col-2 col-form-label">@lang('customer.fields.code_sign')</label>
                                <div class="col-md-10">
                                    <template v-if="mode == 'create' || mode == 'edit'">
                                        <input id="inputCodeSign" name="code_sign" v-model="customer.code_sign" type="text" class="form-control" placeholder="@lang('customer.fields.code_sign')">
                                    </template>
                                    <template v-if="mode == 'show'">
                                        <div class="form-control-plaintext">@{{ customer.code_sign }}</div>
                                    </template>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="inputAddress" class="col-2 col-form-label">@lang('customer.fields.address')</label>
                                <div class="col-md-10">
                                    <template v-if="mode == 'create' || mode == 'edit'">
                                        <textarea name="address" v-model="customer.address" id="inputAddress" class="form-control" rows="4"></textarea>
                                    </template>
                                    <template v-if="mode == 'show'">
                                        <div class="form-control-plaintext">@{{ customer.address }}</div>
                                    </template>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="inputCity" class="col-2 col-form-label">@lang('customer.fields.city')</label>
                                <div class="col-md-10">
                                    <template v-if="mode == 'create' || mode == 'edit'">
                                        <input id="inputCity" name="city" v-model="customer.city" type="text" class="form-control" placeholder="@lang('customer.fields.city')">
                                    </template>
                                    <template v-if="mode == 'show'">
                                        <div class="form-control-plaintext">@{{ customer.city }}</div>
                                    </template>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="inputPhone" class="col-2 col-form-label">@lang('customer.fields.phone')</label>
                                <div class="col-md-10">
                                    <template v-if="mode == 'create' || mode == 'edit'">
                                        <input id="inputPhone" name="phone_number" type="text" v-model="customer.phone_number" class="form-control" placeholder="@lang('customer.fields.phone')">
                                    </template>
                                    <template v-if="mode == 'show'">
                                        <div class="form-control-plaintext">@{{ customer.phone_number }}</div>
                                    </template>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="inputFax" class="col-2 col-form-label">@lang('customer.fields.fax_num')</label>
                                <div class="col-md-10">
                                    <template v-if="mode == 'create' || mode == 'edit'">
                                        <input id="inputFax" name="fax_num" type="text" v-model="customer.fax_num" class="form-control" placeholder="@lang('customer.fields.fax_num')">
                                    </template>
                                    <template v-if="mode == 'show'">
                                        <div class="form-control-plaintext">@{{ customer.fax_num }}</div>
                                    </template>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="inputTaxId" class="col-2 col-form-label">@lang('customer.fields.tax_id')</label>
                                <div class="col-md-10">
                                    <template v-if="mode == 'create' || mode == 'edit'">
                                        <input id="inputTaxId" name="tax_id" type="text" v-model="customer.tax_id" class="form-control" placeholder="@lang('customer.fields.tax_id')">
                                    </template>
                                    <template v-if="mode == 'show'">
                                        <div class="form-control-plaintext">@{{ customer.tax_id }}</div>
                                    </template>
                                </div>
                            </div>
                            <div v-bind:class="{ 'form-group row':true, 'is-invalid':errors.has('tabs_customer.status') }">
                                <label for="inputStatus" class="col-2 col-form-label">@lang('customer.fields.status')</label>
                                <div class="col-md-10">
                                    <template v-if="mode == 'create' || mode == 'edit'">
                                        <select id="inputStatus"
                                                class="form-control"
                                                name="status"
                                                v-model="customer.status"
                                                v-validate="'required'"
                                                data-vv-as="{{ trans('customer.fields.status') }}"
                                                data-vv-scope="tabs_customer">
                                            <option v-bind:value="defaultPleaseSelect">@lang('labels.PLEASE_SELECT')</option>
                                            <option v-for="(s, sIdx) in statusDDL" v-bind:value="s.code">@{{ s.description }}</option>
                                        </select>
                                        <span v-show="errors.has('tabs_customer.status')" class="invalid-feedback">@{{ errors.first('tabs_customer.status') }}</span>
                                    </template>
                                    <template v-if="mode == 'show'">
                                        <div class="form-control-plaintext">@{{ customer.statusI18n }}</div>
                                    </template>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="inputRemarks" class="col-2 col-form-label">@lang('customer.fields.remarks')</label>
                                <div class="col-md-10">
                                    <template v-if="mode == 'create' || mode == 'edit'">
                                        <input id="inputRemarks" name="remarks" v-model="customer.remarks" type="text" class="form-control" placeholder="@lang('customer.fields.remarks')">
                                    </template>
                                    <template v-if="mode == 'show'">
                                        <div class="form-control-plaintext">@{{ customer.remarks }}</div>
                                    </template>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade fade-up show" id="tabs_pic" role="tabpanel">
                            <div class="row">
                                <div class="col-2">
                                    <template v-if="mode == 'create' || mode == 'edit'">
                                        <button type="button" class="btn btn-sm btn-default" v-on:click="addNewPIC">@lang('buttons.create_new_button')</button>
                                    </template>
                                    <template v-if="mode == 'show'">
                                    </template>
                                </div>
                                <div class="col-10">
                                    <div v-for="(p, pIdx) in customer.persons_in_charge">
                                        <div class="block block-shadow-on-hover block-mode-loading-refresh">
                                            <div class="block-header block-header-default">
                                                <h3 class="block-title">@lang('customer.index.panel.pic.title')&nbsp;@{{ pIdx + 1 }}</h3>
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
                                                    <label for="inputFirstName" class="col-2 col-form-label">@lang('customer.fields.first_name')</label>
                                                    <div class="col-md-10">
                                                        <template v-if="mode == 'create' || mode == 'edit'">
                                                            <input type="hidden" name="profile_id[]" v-model="p.hId"/>
                                                            <input id="inputFirstName" type="text" name="first_name[]" class="form-control" v-model="p.first_name" placeholder="@lang('customer.fields.first_name')"
                                                                   v-validate="'required'" v-bind:data-vv-as="'{{ trans('customer.fields.first_name') }} ' + (pIdx + 1)" v-bind:data-vv-name="'first_name_' + pIdx"
                                                                   data-vv-scope="tabs_pic">
                                                            <span v-show="errors.has('tabs_pic.first_name_' + pIdx)" class="invalid-feedback">@{{ errors.first('tabs_pic.first_name_' + pIdx) }}</span>
                                                        </template>
                                                        <template v-if="mode == 'show'">
                                                            <div class="form-control-plaintext">@{{ p.first_name }}</div>
                                                        </template>
                                                    </div>
                                                </div>
                                                <div v-bind:class="{ 'form-group row':true, 'is-invalid':errors.has('tabs_pic.last_name_' + pIdx) }">
                                                    <label for="inputLastName" class="col-2 col-form-label">@lang('customer.fields.last_name')</label>
                                                    <div class="col-md-10">
                                                        <template v-if="mode == 'create' || mode == 'edit'">
                                                            <input id="inputLastName" type="text" name="last_name[]" class="form-control" v-model="p.last_name" placeholder="@lang('customer.fields.last_name')"
                                                                   v-validate="'required'" v-bind:data-vv-as="'{{ trans('customer.fields.last_name') }} ' + (pIdx + 1)" v-bind:data-vv-name="'last_name_' + pIdx"
                                                                   data-vv-scope="tabs_pic">
                                                            <span v-show="errors.has('tabs_pic.last_name_' + pIdx)" class="invalid-feedback">@{{ errors.first('tabs_pic.last_name_' + pIdx) }}</span>
                                                        </template>
                                                        <template v-if="mode == 'show'">
                                                            <div class="form-control-plaintext">@{{ p.last_name }}</div>
                                                        </template>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="inputAddress" class="col-2 col-form-label">@lang('customer.fields.address')</label>
                                                    <div class="col-md-10">
                                                        <template v-if="mode == 'create' || mode == 'edit'">
                                                            <input id="inputAddress" type="text" name="profile_address[]" class="form-control" v-model="p.address" placeholder="@lang('customer.fields.address')">
                                                        </template>
                                                        <template v-if="mode == 'show'">
                                                            <div class="form-control-plaintext">@{{ p.address }}</div>
                                                        </template>
                                                    </div>
                                                </div>
                                                <div v-bind:class="{ 'form-group row':true, 'is-invalid':errors.has('tabs_pic.ic_num_' + pIdx) }">
                                                    <label for="inputICNum" class="col-2 col-form-label">@lang('customer.fields.ic_num')</label>
                                                    <div class="col-md-10">
                                                        <template v-if="mode == 'create' || mode == 'edit'">
                                                            <input id="inputICNum" type="text" name="ic_num[]" class="form-control" v-model="p.ic_num" placeholder="@lang('customer.fields.ic_num')"
                                                                   v-validate="'required'" v-bind:data-vv-as="'{{ trans('customer.fields.ic_num') }} ' + (pIdx + 1)" v-bind:data-vv-name="'ic_num_' + pIdx"
                                                                   data-vv-scope="tabs_pic">
                                                            <span v-show="errors.has('tabs_pic.ic_num_' + pIdx)" class="invalid-feedback">@{{ errors.first('tabs_pic.ic_num_' + pIdx) }}</span>
                                                        </template>
                                                        <template v-if="mode == 'show'">
                                                            <div class="form-control-plaintext">@{{ p.ic_num }}</div>
                                                        </template>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="inputPhoneNumber" class="col-2 col-form-label">@lang('customer.fields.phone_number')</label>
                                                    <div class="col-md-10">
                                                        <table class="table table-bordered">
                                                            <thead class="thead-light">
                                                                <tr>
                                                                    <th>@lang('customer.index.table.table_phone.header.provider')</th>
                                                                    <th>@lang('customer.index.table.table_phone.header.number')</th>
                                                                    <th>@lang('customer.index.table.table_phone.header.remarks')</th>
                                                                    <th class="text-center">@lang('labels.ACTION')</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr v-for="(ph, phIdx) in p.phone_numbers">
                                                                    <td v-bind:class="{ 'is-invalid':errors.has('tabs_pic.profile_' + pIdx + '_phoneprovider_' + phIdx) }">
                                                                        <input type="hidden" v-bind:name="'profile_' + pIdx + '_phone_numbers_id[]'" v-model="ph.hId"/>
                                                                        <template v-if="mode == 'create' || mode == 'edit'">
                                                                            <select v-bind:name="'profile_' + pIdx + '_phone_provider[]'" class="form-control" v-model="ph.phoneProviderHId"
                                                                                    v-validate="'required'" v-bind:data-vv-as="'{{ trans('customer.index.table.table_phone.header.provider') }} ' + (phIdx + 1)"
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
                                                                                   v-validate="'required'" v-bind:data-vv-as="'{{ trans('customer.index.table.table_phone.header.number') }} ' + (phIdx + 1)"
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
                                                                            <button type="button" class="btn btn-sm btn-danger" v-bind:data="phIdx" v-on:click="removeSelectedPhone(pIdx, phIdx)">
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
                                                            <button type="button" class="btn btn-sm btn-default" v-on:click="addNewPhone(pIdx)">@lang('buttons.create_new_button')</button>
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
                                        <th class="text-center">@lang('customer.index.table.table_bank.header.bank')</th>
                                        <th class="text-center">@lang('customer.index.table.table_bank.header.account_name')</th>
                                        <th class="text-center">@lang('customer.index.table.table_bank.header.account_number')</th>
                                        <th class="text-center">@lang('customer.index.table.table_bank.header.remarks')</th>
                                        <th class="text-center">@lang('labels.ACTION')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(ba, baIdx) in customer.bank_accounts">
                                        <td v-bind:class="{ 'is-invalid':errors.has('tabs_bankaccounts.bank_' + baIdx) }">
                                            <input type="hidden" name="bank_account_id[]" v-model="ba.hId"/>
                                            <template v-if="mode == 'create' || mode == 'edit'">
                                                <select class="form-control"
                                                        name="bank_id[]"
                                                        v-model="ba.bankHId"
                                                        v-validate="'required'"
                                                        v-bind:data-vv-as="'{{ trans('customer.index.table.table_bank.header.bank') }} ' + (baIdx + 1)"
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
                                                       v-bind:data-vv-as="'{{ trans('customer.index.table.table_bank.header.account_name') }} ' + (baIdx + 1)"
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
                                                       v-validate="'required|numeric'" v-bind:data-vv-as="'{{ trans('customer.index.table.table_bank.header.account_number') }} ' + (baIdx + 1)"
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
                                                <button type="button" class="btn btn-sm btn-danger" v-bind:data="baIdx" v-on:click="removeSelectedBank(baIdx)"><span class="fa fa-close fa-fw"></span></button>
                                            </template>
                                            <template v-if="mode == 'show'">
                                                <div class="form-control-plaintext">&nbsp;</div>
                                            </template>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <template v-if="mode == 'create' || mode == 'edit'">
                                <button class="btn btn-sm btn-default" type="button" v-on:click="addNewBankAccount">@lang('buttons.create_new_button')</button>
                            </template>
                            <template v-if="mode == 'show'">
                            </template>
                        </div>
                        <div class="tab-pane fade fade-up show" id="tabs_settings" role="tabpanel">
                            <div v-bind:class="{ 'form-group row':true, 'is-invalid':errors.has('tabs_settings.payment_due_day') }">
                                <label for="inputPaymentDueDay" class="col-2 col-form-label">@lang('customer.fields.payment_due_day')</label>
                                <div class="col-md-10">
                                    <template v-if="mode == 'create' || mode == 'edit'">
                                        <input id="inputPaymentDueDay" name="payment_due_day" v-model="customer.payment_due_day" type="text" class="form-control"  placeholder="{{ trans('customer.fields.payment_due_day') }}"
                                               v-validate="'required|numeric|max_value:100'" data-vv-as="{{ trans('customer.fields.payment_due_day') }}" data-vv-scope="tabs_settings">
                                        <span v-show="errors.has('tabs_settings.payment_due_day')" class="invalid-feedback">@{{ errors.first('payment_due_day') }}</span>
                                    </template>
                                    <template v-if="mode == 'show'">
                                        <div class="form-control-plaintext">@{{ customer.payment_due_day }}</div>
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

@section('ziggy')
    @routes('customer')
@endsection

@section('custom_js')
    <script type="application/javascript">

    </script>
    <script type="application/javascript" src="{{ mix('js/apps/customer.js') }}"></script>
@endsection
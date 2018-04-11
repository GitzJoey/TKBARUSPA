@extends('layouts.codebase.master')

@section('title')
    @lang('company.index.title')
@endsection

@section('page_title')
    @lang('company.index.page_title')
@endsection

@section('page_title_desc')
    @lang('company.index.page_title_desc')
@endsection

@section('breadcrumbs')
    {!! Breadcrumbs::render('settings_company') !!}
@endsection

@section('content')
    <div id="companyVue">
        @include ('layouts.common.error')
        <div class="block block-shadow-on-hover block-mode-loading-refresh" id="companyListBlock">
            <div class="block-header block-header-default">
                <h3 class="block-title">@lang('company.index.panel.list_panel.title')</h3>
                <div class="block-options">
                    <button type="button" class="btn-block-option" data-toggle="block-option" data-action="fullscreen_toggle"></button>
                    <button type="button" class="btn-block-option" v-on:click="getAllCompany">
                        <i class="si si-refresh"></i>
                    </button>
                    <button type="button" class="btn-block-option" data-toggle="block-option" data-action="content_toggle"></button>
                </div>
            </div>
            <div class="block-content">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-vcenter">
                        <thead class="thead-light">
                            <th>@lang('company.index.table.company_list.header.name')</th>
                            <th>@lang('company.index.table.company_list.header.address')</th>
                            <th>@lang('company.index.table.company_list.header.tax_id')</th>
                            <th>@lang('company.index.table.company_list.header.default')</th>
                            <th>@lang('company.index.table.company_list.header.frontweb')</th>
                            <th>@lang('company.index.table.company_list.header.status')</th>
                            <th>@lang('company.index.table.company_list.header.remarks')</th>
                            <th class="text-center action-column-width">@lang('labels.ACTION')</th>
                        </thead>
                        <tbody>
                            <tr v-for="(c, cIdx) in companyList">
                                <td>@{{ c.name }}</td>
                                <td>@{{ c.address }}</td>
                                <td>@{{ c.tax_id }}</td>
                                <td>@{{ c.defaultI18n }}</td>
                                <td>@{{ c.frontwebI18n }}</td>
                                <td>@{{ c.statusI18n }}</td>
                                <td>@{{ c.remarks }}</td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <button class="btn btn-sm btn-secondary" v-on:click="showSelected(cIdx)"><span class="fa fa-info fa-fw"></span></button>
                                        <button class="btn btn-sm btn-secondary" v-on:click="editSelected(cIdx)"><span class="fa fa-pencil fa-fw"></span></button>
                                        <button class="btn btn-sm btn-secondary" v-on:click="deleteSelected(c.hId)"><span class="fa fa-close fa-fw"></span></button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <br>
                <div class="row items-push-2x text-center text-sm-left">
                    <div class="col-sm-6 col-xl-4">
                        <button type="button" class="btn btn-primary btn-lg btn-circle" v-on:click="createNew" data-toggle="tooltip" data-placement="top" title="{{ Lang::get('buttons.create_new_button') }}">
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
        <div class="block block-shadow-on-hover block-mode-loading-refresh" id="companyCRUDBlock">
            <div class="block-header block-header-default">
                <h3 class="block-title">
                    <template v-if="mode == 'create'">@lang('company.index.panel.crud_panel.title_create')</template>
                    <template v-if="mode == 'show'">@lang('company.index.panel.crud_panel.title_show')</template>
                    <template v-if="mode == 'edit'">@lang('company.index.panel.crud_panel.title_edit')</template>
                </h3>
                <div class="block-options">
                    <button type="button" class="btn-block-option" data-toggle="block-option" data-action="fullscreen_toggle"></button>
                    <button type="button" class="btn-block-option" data-toggle="block-option" data-action="content_toggle"></button>
                </div>
            </div>
            <div class="block-content">
                <form id="companyForm" method="post" v-on:submit.prevent="validateBeforeSubmit">
                    <ul class="nav nav-tabs nav-tabs-alt" data-toggle="tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" href="#tabs_company">
                                @lang('company.index.tabs.company')
                                <template v-if="errors.any('tabs_company')">
                                    &nbsp;<span id="companyDataTabError" class="red-asterisk"><i class="fa fa-close fa-fw"></i></span>
                                </template>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#tabs_bankaccount">
                                @lang('company.index.tabs.bank_account')
                                <template v-if="errors.any('tabs_bankAccounts')">
                                    &nbsp;<span id="bankAccountTabError" class="red-asterisk"><i class="fa fa-close fa-fw"></i></span>
                                </template>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#tabs_settings">
                                @lang('company.index.tabs.settings')
                                <template v-if="errors.any('tabs_settings')">
                                    &nbsp;<span id="settingsTabError" class="red-asterisk"><i class="fa fa-close fa-fw"></i></span>
                                </template>
                            </a>
                        </li>
                    </ul>
                    <div class="block-content tab-content overflow-hidden">
                        <div class="tab-pane fade fade-up show active" id="tabs_company" role="tabpanel">
                            <div v-bind:class="{ 'form-group':true, 'row':true, 'is-invalid':errors.has('tabs_company.name') }">
                                <label for="inputCompanyName" class="col-2">@lang('company.fields.name')</label>
                                <div class="col-md-10">
                                    <template v-if="mode == 'create' || mode == 'edit'">
                                        <input id="inputCompanyName" name="name" type="text" class="form-control" placeholder="{{ trans('company.fields.name') }}"
                                               v-model="company.name" v-validate="'required'" data-vv-as="{{ trans('company.fields.name') }}" data-vv-scope="tabs_company">
                                        <span v-show="errors.has('tabs_company.name')" class="invalid-feedback">@{{ errors.first('tabs_company.name') }}</span>
                                    </template>
                                    <template v-if="mode == 'show'">
                                        <div class="form-control-plaintext">@{{ company.name }}</div>
                                    </template>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="inputImage" class="col-2 col-form-label">@lang('company.fields.logo')</label>
                                <div class="col-md-10">
                                    <template v-if="mode == 'create' || mode == 'edit'">
                                        <img class="img-avatar128" src="http://localhost:8000/images/no_image.png"/>
                                        <input type="file" id="inputImage" name="image_path">
                                    </template>
                                    <template v-if="mode == 'show'">
                                        <div class="form-control-plaintext">
                                            <img class="img-avatar128" src="http://localhost:8000/images/no_image.png"/>
                                        </div>
                                    </template>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="inputAddress" class="col-2 col-form-label">@lang('company.fields.address')</label>
                                <div class="col-md-9">
                                    <template v-if="mode == 'create' || mode == 'edit'">
                                        <textarea id="inputAddress" v-model="company.address" class="form-control" rows="5" name="address"></textarea>
                                    </template>
                                    <template v-if="mode == 'show'">
                                        <div class="form-control-plaintext">@{{ company.address  }}</div>
                                    </template>
                                </div>
                                <div class="col-md-1">
                                    <template v-if="mode == 'create' || mode == 'edit'">
                                        <button id="btnChooseLocation" type="button" class="btn btn-default" data-toggle="modal" data-target="#myModal"><i class="fa fa-location-arrow"></i></button>
                                        <input id="inputLatitude" type="hidden" name="latitude" v-model="company.latitude">
                                        <input id="inputLongitude" type="hidden" name="longitude" v-model="company.longitude">
                                    </template>
                                    <template v-if="mode == 'show'">
                                        <div class="form-control-plaintext"></div>
                                    </template>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="inputPhone" class="col-2 col-form-label">@lang('company.fields.phone')</label>
                                <div class="col-md-10">
                                    <template v-if="mode == 'create' || mode == 'edit'">
                                        <input id="inputPhone" name="phone_num" v-model="company.phone_num" type="text" class="form-control" placeholder="{{ trans('company.fields.phone') }}">
                                    </template>
                                    <template v-if="mode == 'show'">
                                        <div class="form-control-plaintext">@{{ company.phone_num }}</div>
                                    </template>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="inputFax" class="col-2 col-form-label">@lang('company.fields.fax')</label>
                                <div class="col-md-10">
                                    <template v-if="mode == 'create' || mode == 'edit'">
                                        <input id="inputFax" name="fax_num" type="text" v-model="company.fax_num" class="form-control" placeholder="{{ trans('company.fields.fax')}}">
                                    </template>
                                    <template v-if="mode == 'show'">
                                        <div class="form-control-plaintext">@{{ company.fax_num }}</div>
                                    </template>
                                </div>
                            </div>
                            <div v-bind:class="{ 'form-group':true, 'row':true, 'is-invalid':errors.has('tabs_company.tax_id') }">
                                <label for="inputTax" class="col-2 col-form-label">@lang('company.fields.tax_id')</label>
                                <div class="col-md-10">
                                    <template v-if="mode == 'create' || mode == 'edit'">
                                        <input id="inputTax" name="tax_id" type="text" class="form-control" placeholder="{{ trans('company.fields.tax_id') }}"
                                               v-model="company.tax_id" v-validate="'required'" data-vv-as="{{ trans('company.fields.tax_id') }}" data-vv-scope="tabs_company">
                                        <span v-show="errors.has('tabs_company.tax_id')" class="invalid-feedback">@{{ errors.first('tabs_company.tax_id') }}</span>
                                    </template>
                                    <template v-if="mode == 'show'">
                                        <div class="form-control-plaintext">@{{ company.tax_id }}</div>
                                    </template>
                                </div>
                            </div>
                            <div v-bind:class="{ 'form-group':true, 'row':true, 'is-invalid':errors.has('tabs_company.status') }">
                                <label for="inputStatus" class="col-2 col-form-label">@lang('company.fields.status')</label>
                                <div class="col-md-10">
                                    <template v-if="mode == 'create' || mode == 'edit'">
                                        <select class="form-control"
                                                name="status"
                                                v-model="company.status"
                                                v-validate="'required'"
                                                data-vv-as="{{ trans('company.fields.status') }}"
                                                data-vv-scope="tabs_company">
                                            <option v-bind:value="defaultPleaseSelect">@lang('labels.PLEASE_SELECT')</option>
                                            <option v-for="(s, sIdx) in statusDDL" v-bind:value="s.code">@{{ s.description }}</option>
                                        </select>
                                        <span v-show="errors.has('tabs_company.status')" class="invalid-feedback">@{{ errors.first('tabs_company.status') }}</span>
                                    </template>
                                    <template v-if="mode == 'show'">
                                        <div class="form-control-plaintext">@{{ company.statusI18n }}</div>
                                    </template>
                                </div>
                            </div>
                            <div v-bind:class="{ 'form-group':true, 'row':true, 'is-invalid':errors.has('tabs_company.is_default') }">
                                <label for="inputIsDefault" class="col-2 col-form-label">@lang('company.fields.default')</label>
                                <div class="col-md-10">
                                    <template v-if="mode == 'create' || mode == 'edit'">
                                        <select class="form-control"
                                                name="is_default"
                                                v-model="company.is_default"
                                                v-validate="'required'"
                                                data-vv-as="{{ trans('company.fields.default') }}"
                                                data-vv-scope="tabs_company">
                                            <option v-bind:value="defaultPleaseSelect">@lang('labels.PLEASE_SELECT')</option>
                                            <option v-for="(yn, ynIdx) in yesnoDDL" v-bind:value="yn.code">@{{ yn.description }}</option>
                                        </select>
                                        <span v-show="errors.has('tabs_company.is_default')" class="invalid-feedback">@{{ errors.first('tabs_company.is_default') }}</span>
                                    </template>
                                    <template v-if="mode == 'show'">
                                        <div class="form-control-plaintext">@{{ company.defaultI18n }}</div>
                                    </template>
                                </div>
                            </div>
                            <div v-bind:class="{ 'form-group':true, 'row':true, 'is-invalid':errors.has('tabs_company.frontweb') }">
                                <label for="inputFrontWeb" class="col-2 col-form-label">@lang('company.fields.frontweb')</label>
                                <div class="col-md-10">
                                    <template v-if="mode == 'create' || mode == 'edit'">
                                        <select class="form-control"
                                                name="frontweb"
                                                v-model="company.frontweb"
                                                v-validate="'required'"
                                                data-vv-as="{{ trans('company.fields.frontweb') }}"
                                                data-vv-scope="tabs_company">
                                            <option v-bind:value="defaultPleaseSelect">@lang('labels.PLEASE_SELECT')</option>
                                            <option v-for="(yn, ynIdx) in yesnoDDL" v-bind:value="yn.code">@{{ yn.description }}</option>
                                        </select>
                                        <span v-show="errors.has('tabs_company.frontweb')" class="invalid-feedback">@{{ errors.first('tabs_company.frontweb') }}</span>
                                    </template>
                                    <template v-if="mode == 'show'">
                                        <div class="form-control-plaintext">@{{ company.frontwebI18n }}</div>
                                    </template>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="inputRemarks" class="col-2 col-form-label">@lang('company.fields.remarks')</label>
                                <div class="col-sm-10">
                                    <template v-if="mode == 'create' || mode == 'edit'">
                                        <input id="inputRemarks" name="remarks" v-model="company.remarks" type="text" class="form-control" placeholder="{{ trans('company.fields.remarks') }}">
                                    </template>
                                    <template v-if="mode == 'show'">
                                        <div class="form-control-plaintext">@{{ company.remarks }}</div>
                                    </template>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade fade-up" id="tabs_bankaccount" role="tabpanel">
                            <table class="table table-bordered">
                                <thead class="thead-light">
                                    <tr>
                                        <th class="text-center">@lang('company.index.table.bank_list.header.bank')</th>
                                        <th class="text-center">@lang('company.index.table.bank_list.header.account_name')</th>
                                        <th class="text-center">@lang('company.index.table.bank_list.header.account_number')</th>
                                        <th class="text-center">@lang('company.index.table.bank_list.header.remarks')</th>
                                        <th class="text-center">@lang('labels.ACTION')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(ba, baIdx) in company.bank_accounts">
                                        <td v-bind:class="{ 'is-invalid':errors.has('tabs_bankAccounts.bank_id_' + baIdx) }">
                                            <template v-if="mode == 'create' || mode == 'edit'">
                                                <select class="form-control"
                                                        name="bank_id[]"
                                                        v-model="ba.bankHId"
                                                        v-validate="'required'"
                                                        v-bind:data-vv-as="'{{ trans('company.index.fields.bank_id') }} ' + (baIdx + 1)"
                                                        v-bind:data-vv-name="'bank_id_' + baIdx"
                                                        data-vv-scope="tabs_bankAccounts">
                                                    <option v-bind:value="defaultPleaseSelect">@lang('labels.PLEASE_SELECT')</option>
                                                    <option v-for="(b, bIdx) in bankDDL" v-bind:value="b.hId">@{{ b.bankFullName }}</option>
                                                </select>
                                            </template>
                                            <template v-if="mode == 'show'">
                                                <div class="form-control-plaintext">@{{ ba.bank.bankFullName }}</div>
                                            </template>
                                        </td>
                                        <td v-bind:class="{ 'is-invalid':errors.has('tabs_bankAccounts.account_name_' + baIdx) }">
                                            <template v-if="mode == 'create' || mode == 'edit'">
                                                <input type="text" class="form-control" name="account_name[]" v-model="ba.account_name"
                                                       v-validate="'required'" v-bind:data-vv-as="'{{ trans('company.index.fields.account_name') }} ' + (baIdx + 1)"
                                                       v-bind:data-vv-name="'account_name_' + baIdx" data-vv-scope="tabs_bankAccounts">
                                            </template>
                                            <template v-if="mode == 'show'">
                                                <div class="form-control-plaintext">@{{ ba.account_name }}</div>
                                            </template>
                                        </td>
                                        <td v-bind:class="{ 'is-invalid':errors.has('tabs_bankAccounts.account_number_' + baIdx) }">
                                            <template v-if="mode == 'create' || mode == 'edit'">
                                                <input type="text" class="form-control" name="account_number[]" v-model="ba.account_number"
                                                       v-validate="'required|numeric'" v-bind:data-vv-as="'{{ trans('company.index.fields.account_number') }} ' + (baIdx + 1)"
                                                       v-bind:data-vv-name="'account_number_' + baIdx" data-vv-scope="tabs_bankAccounts">
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
                                        <td class="text-center valign-middle">
                                            <template v-if="mode == 'create' || mode == 'edit'">
                                                <button type="button" class="btn btn-xs btn-danger" v-bind:data="baIdx" v-on:click="removeSelectedBankAccounts(baIdx)"><span class="fa fa-close fa-fw"></span></button>
                                            </template>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <template v-if="mode == 'create' || mode == 'edit'">
                                <button class="btn btn-sm btn-default" type="button" v-on:click="addBankAccounts">@lang('buttons.create_new_button')</button>
                            </template>
                        </div>
                        <div class="tab-pane fade fade-up" id="tabs_settings" role="tabpanel">
                            <div class="form-group row">
                                <label for="inputDateFormat" class="col-2 col-form-label">@lang('company.fields.date_format')</label>
                                <div class="col-md-10">
                                    <template v-if="mode == 'create' || mode == 'edit'">
                                        <select name="date_format" class="form-control" v-model="company.date_format">
                                            <option value="d M Y" v-bind:selected="company.phpDateFormat == 'd M Y'">DD MMM YYYY (@{{ displayDateTimeNow('DD MMM YYYY') }}) (default)</option>
                                            <option value="d-m-Y" v-bind:selected="company.phpDateFormat == 'd-m-Y'">DD-MM-YYYY (@{{ displayDateTimeNow('DD-M-YYYY') }})</option>
                                            <option value="d/M/Y" v-bind:selected="company.phpDateFormat == 'd/M/Y'">DD/MM/YYYY (@{{ displayDateTimeNow('D/MMM/YYYY') }})</option>
                                        </select>
                                    </template>
                                    <template v-if="mode == 'show'">
                                        <div class="form-control-plaintext">@{{ company.momentDateFormat }}</div>
                                    </template>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="inputTimeFormat" class="col-2 col-form-label">@lang('company.fields.time_format')</label>
                                <div class="col-md-10">
                                    <template v-if="mode == 'create' || mode == 'edit'">
                                        <select name="time_format" class="form-control" v-model="company.time_format">
                                            <option value="G:H:s" v-bind:selected="company.phpTimeFormat == 'G:H:s'">HH:MM:SS (@{{ displayDateTimeNow('hh:mm:ss') }}) (default)</option>
                                            <option value="g:h A" v-bind:selected="company.phpTimeFormat == 'g:h A'">HH:MM A (@{{ displayDateTimeNow('h:m A') }})</option>
                                        </select>
                                    </template>
                                    <template v-if="mode == 'show'">
                                        <div class="form-control-plaintext">@{{ company.momentTimeFormat }}</div>
                                    </template>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="inputThousandSeparator" class="col-2 col-form-label">@lang('company.fields.thousand_separator')</label>
                                <div class="col-md-10">
                                    <template v-if="mode == 'create' || mode == 'edit'">
                                        <select name="thousand_separator" class="form-control" v-model="company.thousand_separator">
                                            <option value="," v-bind:selected="company.thousand_separator == ','">@lang('company.fields.comma')&nbsp;-&nbsp;1,000,000 (Default)</option>
                                            <option value="." v-bind:selected="company.thousand_separator == '.'">@lang('company.fields.dot')&nbsp;-&nbsp;1.000.000</option>
                                            <option value=" " v-bind:selected="company.thousand_separator == ' '">>@lang('company.fields.space')&nbsp;-&nbsp;1 000 000</option>
                                        </select>
                                    </template>
                                    <template v-if="mode == 'show'">
                                        <div class="form-control-plaintext">@{{ company.thousand_separator }}</div>
                                    </template>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="inputDecimalSeparator" class="col-2 col-form-label">@lang('company.fields.decimal_separator')</label>
                                <div class="col-md-10">
                                    <template v-if="mode == 'create' || mode == 'edit'">
                                        <select name="decimal_separator" class="form-control" v-model="company.decimal_separator">
                                            <option value="." bind:selected="company.decimal_separator == ','">@lang('company.fields.dot')&nbsp;-&nbsp;0.00 (Default)</option>
                                            <option value="," v-bind:selected="company.decimal_separator == ','">@lang('company.fields.comma')&nbsp;-&nbsp;0,00</option>
                                            <option value=" " v-bind:selected="company.decimal_separator == ','">@lang('company.fields.space')&nbsp;-&nbsp;0 00</option>
                                        </select>
                                    </template>
                                    <template v-if="mode == 'show'">
                                        <div class="form-control-plaintext">@{{ company.decimal_separator }}</div>
                                    </template>
                                </div>
                            </div>
                            <div v-bind:class="{ 'form-group':true, 'row':true, 'is-invalid':errors.has('tabs_company.decimal_digit') }">
                                <label for="inputDecimalDigit" class="col-2 col-form-label">@lang('company.fields.decimal_digit')</label>
                                <div class="col-md-10">
                                    <template v-if="mode == 'create' || mode == 'edit'">
                                        <input id="inputDecimalDigit" name="decimal_digit" type="text" class="form-control"
                                               v-model="company.decimal_digit"
                                               v-validate="'required|max_value:4|min_value:0|numeric'" data-vv-as="{{ trans('company.fields.decimal_digit') }}">
                                        <span v-show="errors.has('decimal_digit')" class="invalid-feedback">@{{ errors.first('decimal_digit') }}</span>
                                    </template>
                                    <template v-if="mode == 'show'">
                                        <div class="form-control-plaintext">@{{ company.decimal_digit }}</div>
                                    </template>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="inputRibbon" class="col-2 col-form-label">@lang('company.fields.color_theme')</label>
                                <div class="col-md-10">
                                    <template v-if="mode == 'create' || mode == 'edit'">
                                        <div class="custom-control custom-radio mb-5">
                                            <input class="custom-control-input" type="radio" name="ribbon" id="inputRibbonDefault" value="default"
                                                   v-model="company.ribbon"
                                                   v-bind:checked="company.ribbon == 'default'">
                                            <label class="custom-control-label text-default" for="inputRibbonDefault">Default</label>
                                        </div>
                                        <div class="custom-control custom-radio mb-5">
                                            <input class="custom-control-input" type="radio" name="ribbon" id="inputRibbonCorporate" value="corporate"
                                                   v-model="company.ribbon"
                                                   v-bind:checked="company.ribbon == 'corporate'">
                                            <label class="custom-control-label text-corporate" for="inputRibbonCorporate">Corporate</label>
                                        </div>
                                        <div class="custom-control custom-radio mb-5">
                                            <input class="custom-control-input" type="radio" name="ribbon" id="inputRibbonEarth" value="earth"
                                                   v-model="company.ribbon"
                                                   v-bind:checked="company.ribbon == 'earth'">
                                            <label class="custom-control-label text-earth" for="inputRibbonEarth">Earth</label>
                                        </div>
                                        <div class="custom-control custom-radio mb-5">
                                            <input class="custom-control-input" type="radio" name="ribbon" id="inputRibbonElegance" value="elegance"
                                                   v-model="company.ribbon"
                                                   v-bind:checked="company.ribbon == 'elegance'">
                                            <label class="custom-control-label text-elegance" for="inputRibbonElegance">Elegance</label>
                                        </div>
                                        <div class="custom-control custom-radio mb-5">
                                            <input class="custom-control-input" type="radio" name="ribbon" id="inputRibbonFlat" value="flat"
                                                   v-model="company.ribbon"
                                                   v-bind:checked="company.ribbon == 'flat'">
                                            <label class="custom-control-label text-flat" for="inputRibbonFlat">Flat</label>
                                        </div>
                                        <div class="custom-control custom-radio mb-5">
                                            <input class="custom-control-input" type="radio" name="ribbon" id="inputRibbonPulse" value="pulse"
                                                   v-model="company.ribbon"
                                                   v-bind:checked="company.ribbon == 'pulse'">
                                            <label class="custom-control-label text-pulse" for="inputRibbonPulse">Pulse</label>
                                        </div>
                                    </template>
                                    <template v-if="mode == 'show'">
                                        <template v-if="company.ribbon == 'default'"><div class="form-control-plaintext text-default">Default</div></template>
                                        <template v-if="company.ribbon == 'corporate'"><div class="form-control-plaintext text-corporate">Corporate</div></template>
                                        <template v-if="company.ribbon == 'earth'"><div class="form-control-plaintext text-earth">Earth</div></template>
                                        <template v-if="company.ribbon == 'elegance'"><div class="form-control-plaintext text-elegance">Elegance</div></template>
                                        <template v-if="company.ribbon == 'flat'"><div class="form-control-plaintext text-flat">Flat</div></template>
                                        <template v-if="company.ribbon == 'pulse'"><div class="form-control-plaintext text-pulse">Pulse</div></template>
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

    </script>
    <script type="application/javascript" src="{{ mix('js/apps/company.min.js') }}"></script>
@endsection
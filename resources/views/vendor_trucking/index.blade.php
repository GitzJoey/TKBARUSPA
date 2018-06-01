@extends('layouts.codebase.master')

@section('title')
    @lang('vendor_trucking.index.title')
@endsection

@section('page_title')
    <span class="fa fa-ge fa-fw"></span>&nbsp;@lang('vendor_trucking.index.page_title')
@endsection

@section('page_title_desc')
    @lang('vendor_trucking.index.page_title_desc')
@endsection

@section('breadcrumbs')

@endsection

@section('content')
    <div id="vendorTruckingVue">
        @include ('layouts.common.error')
        <div class="block block-shadow-on-hover block-mode-loading-refresh" id="vendorTruckingListBlock">
            <div class="block-header block-header-default">
                <h3 class="block-title">@lang('vendor_trucking.index.panel.list_panel.title')</h3>
                <div class="block-options">
                    <button type="button" class="btn-block-option" data-toggle="block-option" data-action="fullscreen_toggle"></button>
                    <button type="button" class="btn-block-option" v-on:click="getAllVendorTrucking">
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
                                <th class="text-center">@lang('vendor_trucking.index.table.vendor_trucking_list.header.name')</th>
                                <th class="text-center">@lang('vendor_trucking.index.table.vendor_trucking_list.header.address')</th>
                                <th class="text-center">@lang('vendor_trucking.index.table.vendor_trucking_list.header.phone')</th>
                                <th class="text-center">@lang('vendor_trucking.index.table.vendor_trucking_list.header.tax_id')</th>
                                <th class="text-center">@lang('vendor_trucking.index.table.vendor_trucking_list.header.status')</th>
                                <th class="text-center">@lang('vendor_trucking.index.table.vendor_trucking_list.header.remarks')</th>
                                <th class="text-center">@lang('labels.ACTION')</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(vt, vtIdx) in vendorTruckingList">
                                <td>@{{ vt.name }}</td>
                                <td>@{{ vt.address }}</td>
                                <td>@{{ vt.phone_num }}</td>
                                <td>@{{ vt.tax_id }}</td>
                                <td>@{{ vt.statusI18n }}</td>
                                <td>@{{ vt.remarks }}</td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <button class="btn btn-sm btn-secondary" v-on:click="showSelected(vtIdx)"><span class="fa fa-info fa-fw"></span></button>
                                        <button class="btn btn-sm btn-secondary" v-on:click="editSelected(vtIdx)"><span class="fa fa-pencil fa-fw"></span></button>
                                        <button class="btn btn-sm btn-secondary" v-on:click="deleteSelected(vt.hId)"><span class="fa fa-close fa-fw"></span></button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
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
        <div class="block block-shadow-on-hover block-mode-loading-refresh" id="vendorTruckingCRUDBlock">
            <div class="block-header block-header-default">
                <h3 class="block-title">
                    <template v-if="mode == 'create'">@lang('vendor_trucking.index.panel.crud_panel.title_create')</template>
                    <template v-if="mode == 'show'">@lang('vendor_trucking.index.panel.crud_panel.title_show')</template>
                    <template v-if="mode == 'edit'">@lang('vendor_trucking.index.panel.crud_panel.title_edit')</template>
                </h3>
                <div class="block-options">
                    <button type="button" class="btn-block-option" data-toggle="block-option" data-action="fullscreen_toggle"></button>
                    <button type="button" class="btn-block-option" data-toggle="block-option" data-action="content_toggle"></button>
                </div>
            </div>
            <div class="block-content">
                <form id="vendorTruckingForm" method="post" v-on:submit.prevent="validateBeforeSubmit">
                    <div v-bind:class="{ 'form-group row':true, 'is-invalid':errors.has('name') }">
                        <label for="inputName" class="col-2 col-form-label">@lang('vendor_trucking.fields.name')</label>
                        <div class="col-md-10">
                            <template v-if="mode == 'create' || mode == 'edit'">
                                <input id="inputName" name="name" type="text" class="form-control" placeholder="@lang('vendor_trucking.fields.name')"
                                       v-model="vendorTrucking.name" v-validate="'required'" data-vv-as="{{ trans('vendor_trucking.fields.name') }}">
                                <span v-show="errors.has('name')" class="invalid-feedback" v-cloak>@{{ errors.first('name') }}</span>
                            </template>
                            <template v-if="mode == 'show'">
                                <div class="form-control-plaintext">@{{ vendorTrucking.name }}</div>
                            </template>
                        </div>
                    </div>
                    <div v-bind:class="{ 'form-group row':true, 'is-invalid':errors.has('address') }">
                        <label for="inputAddress" class="col-2 col-form-label">@lang('vendor_trucking.fields.address')</label>
                        <div class="col-md-10">
                            <template v-if="mode == 'create' || mode == 'edit'">
                                <textarea id="inputAddress" name="address" class="form-control" placeholder="@lang('vendor_trucking.fields.address')"
                                      v-model="vendorTrucking.address" v-validate="'required'" data-vv-as="{{ trans('vendor_trucking.fields.address') }}" rows="5"></textarea>
                                <span v-show="errors.has('address')" class="invalid-feedback" v-cloak>@{{ errors.first('address') }}</span>
                            </template>
                            <template v-if="mode == 'show'">
                                <div class="form-control-plaintext">@{{ vendorTrucking.address }}</div>
                            </template>
                        </div>
                    </div>
                    <div v-bind:class="{ 'form-group row':true, 'is-invalid':errors.has('tax_id') }">
                        <label for="inputTax" class="col-2 col-form-label">@lang('vendor_trucking.fields.tax_id')</label>
                        <div class="col-md-10">
                            <template v-if="mode == 'create' || mode == 'edit'">
                                <input id="inputName" name="tax_id" type="text" class="form-control" placeholder="@lang('vendor_trucking.fields.tax_id')"
                                       v-model="vendorTrucking.tax_id" v-validate="'required'" data-vv-as="{{ trans('vendor_trucking.fields.tax_id') }}" placeholder="Tax ID">
                                <span v-show="errors.has('tax_id')" class="invalid-feedback" v-cloak>@{{ errors.first('tax_id') }}</span>
                            </template>
                            <template v-if="mode == 'show'">
                                <div class="form-control-plaintext">@{{ vendorTrucking.tax_id }}</div>
                            </template>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputBank" class="col-2 col-form-label">@lang('vendor_trucking.fields.bank')</label>
                        <div class="col-md-10">
                            <table class="table table-bordered">
                                <thead class="thead-light">
                                    <tr>
                                        <th class="text-center">@lang('vendor_trucking.index.table.bank_list.header.bank')</th>
                                        <th class="text-center">@lang('vendor_trucking.index.table.bank_list.header.account_name')</th>
                                        <th class="text-center">@lang('vendor_trucking.index.table.bank_list.header.account_number')</th>
                                        <th class="text-center">@lang('vendor_trucking.index.table.bank_list.header.remarks')</th>
                                        <th class="text-center">@lang('labels.ACTION')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-show="!vendorTrucking.hasOwnProperty('bank_accounts')">
                                        <td colspan="5" class="text-center">
                                            @lang('labels.DATA_NOT_FOUND')
                                        </td>
                                    </tr>
                                    <tr v-for="(ba, baIdx) in vendorTrucking.bank_accounts" v-show="vendorTrucking.hasOwnProperty('bank_accounts')">
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
                                            <input type="hidden" name="bank_account_id[]" v-model="ba.hId"/>
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
                                        <td class="text-center">
                                            <template v-if="mode == 'create' || mode == 'edit'">
                                                <button type="button" class="btn btn-sm btn-danger" v-bind:data="baIdx" v-on:click="removeSelectedBankAccounts(baIdx)"><span class="fa fa-close fa-fw"></span></button>
                                            </template>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <template v-if="mode == 'create' || mode == 'edit'">
                                <button class="btn btn-sm btn-default" type="button" v-on:click="addBankAccounts">@lang('buttons.create_new_button')</button>
                            </template>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputTruckList" class="col-2 col-form-label">@lang('vendor_trucking.fields.truck_list')</label>
                        <div class="col-md-10">
                            <table class="table table-bordered">
                                <thead class="thead-light">
                                    <tr>
                                        <th class="text-center">@lang('vendor_trucking.index.table.truck_list.header.type')</th>
                                        <th class="text-center">@lang('vendor_trucking.index.table.truck_list.header.plate_number')</th>
                                        <th class="text-center">@lang('vendor_trucking.index.table.truck_list.header.inspection_date')</th>
                                        <th class="text-center">@lang('vendor_trucking.index.table.truck_list.header.driver')</th>
                                        <th class="text-center">@lang('vendor_trucking.index.table.truck_list.header.remarks')</th>
                                        <th class="text-center">@lang('labels.ACTION')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(t, tIdx) in vendorTrucking.trucks">
                                        <td width="20%">
                                            <template v-if="mode == 'create' || mode == 'edit'">
                                                <select class="form-control" id="inputTruckType" name="truck_type[]"
                                                        v-model="t.type"
                                                        v-validate="'required'"
                                                        data-vv-as="{{ trans('vendor_trucking.fields.type') }}">
                                                    <option v-bind:value="defaultPleaseSelect">@lang('labels.PLEASE_SELECT')</option>
                                                    <option v-for="(tt, ttIdx) in truckTypeDDL" v-bind:value="tt.code">@{{ tt.description }}</option>
                                                </select>
                                                <div v-show="errors.has('truck_type')" class="invalid-feedback">@{{ errors.first('truck_type') }}</div>
                                            </template>
                                            <template v-if="mode == 'show'">
                                                <div class="form-control-plaintext">@{{ t.typeI18n }}</div>
                                            </template>
                                            <input type="hidden" name="truck_id[]" v-model="t.hId"/>
                                        </td>
                                        <td>
                                            <template v-if="mode == 'create' || mode == 'edit'">
                                                <input id="inputLicensePlate" name="truck_license_plate[]" type="text" class="form-control" placeholder="@lang('vendor_trucking.fields.license_plate')"
                                                       v-model="t.license_plate" v-validate="'required'" data-vv-as="{{ trans('vendor_trucking.fields.license_plate') }}">
                                                <div v-show="errors.has('license_plate')" class="invalid-feedback">@{{ errors.first('license_plate') }}</div>
                                            </template>
                                            <template v-if="mode == 'show'">
                                                <div class="form-control-plaintext">@{{ t.license_plate }}</div>
                                            </template>
                                        </td>
                                        <td>
                                            <template v-if="mode == 'create' || mode == 'edit'">
                                                <div class="input-group">
                                                    <flat-pickr name="truck_inspection_date[]" class="form-control" v-validate="'required'"
                                                            v-model="t.inspection_date" v-bind:config="flatPickrConfig"
                                                            data-vv-as="{{ trans('vendor_trucking.fields.inspection_date') }}"></flat-pickr>
                                                </div>
                                            </template>
                                            <template v-if="mode == 'show'">
                                                <div class="form-control-plaintext">@{{ t.inspection_date }}</div>
                                            </template>
                                        </td>
                                        <td>
                                            <template v-if="mode == 'create' || mode == 'edit'">
                                                <input id="inputDriver" name="truck_driver[]" type="text" class="form-control" placeholder="@lang('vendor_trucking.fields.driver')"
                                                       v-model="t.driver" v-validate="'required'" data-vv-as="{{ trans('vendor_trucking.fields.driver') }}">
                                                <div v-show="errors.has('driver')" class="invalid-feedback">@{{ errors.first('driver') }}</div>
                                            </template>
                                            <template v-if="mode == 'show'">
                                                <div class="form-control-plaintext">@{{ t.driver }}</div>
                                            </template>
                                        </td>
                                        <td>
                                            <template v-if="mode == 'create' || mode == 'edit'">
                                                <input type="text" class="form-control" id="inputRemarks" name="truck_remarks[]" placeholder="@lang('vendor_trucking.fields.remarks')"
                                                       v-model="t.remarks">
                                            </template>
                                            <template v-if="mode == 'show'">
                                                <div class="form-control-plaintext">@{{ t.remarks }}</div>
                                            </template>
                                        </td>
                                        <td class="text-center">
                                            <template v-if="mode == 'create' || mode == 'edit'">
                                                <button type="button" class="btn btn-sm btn-danger" v-on:click="removeTruck(tIdx)"><span class="fa fa-close"></span></button>
                                            </template>
                                            <template v-if="mode == 'show'"></template>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <template v-if="mode == 'create' || mode == 'edit'">
                                <button class="btn btn-sm btn-default" type="button" v-on:click="addTruck">@lang('buttons.create_new_button')</button>
                            </template>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputMaintenanceByCompany" class="col-2 col-form-label">@lang('vendor_trucking.fields.maintenance_by')</label>
                        <div class="col-md-10">
                            <template v-if="mode == 'create' || mode == 'edit'">
                                <label class="css-control css-control-primary css-checkbox css-checkbox-rounded" for="inputMaintenanceByCompany">
                                    <input class="css-control-input" id="inputMaintenanceByCompany" type="checkbox" v-model="vendorTrucking.maintenance_by_company" true-value="1" false-value="0">
                                    <span class="css-control-indicator"></span>&nbsp;@lang('vendor_trucking.fields.company')
                                </label>
                            </template>
                            <template v-if="mode == 'show'">
                            </template>
                            <input type="hidden" v-model="vendorTrucking.maintenance_by_company" name="maintenance_by_company" />
                        </div>
                    </div>
                    <div v-bind:class="{ 'form-group row':true, 'is-invalid':errors.has('status') }">
                        <label for="inputStatus" class="col-2 col-form-label">@lang('vendor_trucking.fields.status')</label>
                        <div class="col-md-10">
                            <template v-if="mode == 'create' || mode == 'edit'">
                                <select class="form-control"
                                        name="status"
                                        v-model="vendorTrucking.status"
                                        v-validate="'required'"
                                        data-vv-as="{{ trans('vendor_trucking.fields.status') }}">
                                    <option v-bind:value="defaultPleaseSelect">@lang('labels.PLEASE_SELECT')</option>
                                    <option v-for="(s, sI) in statusDDL" v-bind:value="s.code">@{{ s.description }}</option>
                                </select>
                                <span v-show="errors.has('status')" class="invalid-feedback" v-cloak>@{{ errors.first('status') }}</span>
                            </template>
                            <template v-if="mode == 'show'">
                                <div class="form-control-plaintext">@{{ vendorTrucking.statusI18n }}</div>
                            </template>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputRemarks" class="col-2 col-form-label">@lang('vendor_trucking.fields.remarks')</label>
                        <div class="col-md-10">
                            <template v-if="mode == 'create' || mode == 'edit'">
                                <input id="inputRemarks" name="remarks" v-model="vendorTrucking.remarks" type="text" class="form-control" value="{{ old('remarks') }}" placeholder="Remarks">
                            </template>
                            <template v-if="mode == 'show'">
                                <div class="form-control-plaintext">@{{ vendorTrucking.remarks }}</div>
                            </template>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-2 col-form-label" for="inputButton">&nbsp;</label>
                        <div class="col-md-10">
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
    @routes('vendor_trucking')
@endsection

@section('custom_js')
    <script type="application/javascript">

    </script>
    <script type="application/javascript" src="{{ mix('js/apps/vendor_trucking.js') }}"></script>
@endsection
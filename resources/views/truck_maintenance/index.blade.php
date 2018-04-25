@extends('layouts.codebase.master')

@section('title')
	@lang('truck_maintenance.index.title')
@endsection

@section('page_title')
	<span class="fa fa-truck fa-flip-horizontal fa-fw"></span>@lang('truck_maintenance.index.page_title')
@endsection

@section('page_title_desc')
	@lang('truck_maintenance.index.page_title_desc')
@endsection

@section('breadcrumbs')

@endsection

@section('content')
    <div id="truckMaintenanceVue">
        @include ('layouts.common.error')
        <div class="block block-shadow-on-hover block-mode-loading-refresh" id="truckMaintenanceListBlock">
            <div class="block-header block-header-default">
                <h3 class="block-title">@lang('truck_maintenance.index.panel.list_panel.title')</h3>
                <div class="block-options">
                    <button type="button" class="btn-block-option" data-toggle="block-option" data-action="fullscreen_toggle"></button>
                    <button type="button" class="btn-block-option" v-on:click="getAllTruckMaintenance">
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
                                <th class="text-center">@lang('truck_maintenance.index.table.truck_maintenance_list.header.plate_number')</th>
                                <th class="text-center">@lang('truck_maintenance.index.table.truck_maintenance_list.header.maintenance_date')</th>
                                <th class="text-center">@lang('truck_maintenance.index.table.truck_maintenance_list.header.maintenance_type')</th>
                                <th class="text-center">@lang('truck_maintenance.index.table.truck_maintenance_list.header.cost')</th>
                                <th class="text-center">@lang('truck_maintenance.index.table.truck_maintenance_list.header.odometer')</th>
                                <th class="text-center">@lang('truck_maintenance.index.table.truck_maintenance_list.header.remarks')</th>
                                <th class="text-center">@lang('labels.ACTION')</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(u, uIdx) in truckMaintenanceList">
                                <td>@{{ u.plate_number }}</td>
                                <td>@{{ u.maintenance_date }}</td>
                                <td>@{{ u.maintenance_type }}</td>
                                <td>@{{ u.cost }}</td>
                                <td>@{{ u.odometer }}</td>
                                <td>@{{ u.remarks }}</td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <button class="btn btn-sm btn-secondary" v-on:click="showSelected(uIdx)"><span class="fa fa-info fa-fw"></span></button>
                                        <button class="btn btn-sm btn-secondary" v-on:click="editSelected(uIdx)"><span class="fa fa-pencil fa-fw"></span></button>
                                        <button class="btn btn-sm btn-secondary" v-on:click="deleteSelected(u.hId)"><span class="fa fa-close fa-fw"></span></button>
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
        <div class="block block-shadow-on-hover block-mode-loading-refresh" id="truckMaintenanceCRUDBlock">
            <div class="block-header block-header-default">
                <h3 class="block-title">
                    <template v-if="mode == 'create'">@lang('truck_maintenance.index.panel.crud_panel.title_create')</template>
                    <template v-if="mode == 'show'">@lang('truck_maintenance.index.panel.crud_panel.title_show')</template>
                    <template v-if="mode == 'edit'">@lang('truck_maintenance.index.panel.crud_panel.title_edit')</template>
                </h3>
                <div class="block-options">
                    <button type="button" class="btn-block-option" data-toggle="block-option" data-action="fullscreen_toggle"></button>
                    <button type="button" class="btn-block-option" data-toggle="block-option" data-action="content_toggle"></button>
                </div>
            </div>
            <div class="block-content">
                <form id="truckMaintenanceForm" method="post" v-on:submit.prevent="validateBeforeSubmit">
                    <input type="hidden" v-model="truckMaintenance.hId" name="hId" value=""/>
                        <div v-bind:class="{ 'form-group row':true, 'is-invalid':errors.has('maintenance_date') }">
                        <label for="inputMaintenanceDate" class="col-2 col-form-label">@lang('truck_maintenance.fields.maintenance_date')</label>
                        <div class="col-md-10">
                            <template v-if="mode == 'create' || mode == 'edit'">
                                <div class="input-group">
                                    <flat-pickr name="maintenance_date" v-model="truckMaintenance.maintenance_date" v-bind:config="flatPickrConfig" class="form-control" v-validate="'required'" data-vv-as="{{ trans('truck_maintenance.fields.maintenance_date') }}"></flat-pickr>
                                </div>
                            </template>
                            <template v-if="mode == 'show'">
                                <div class="form-control-plaintext">@{{ truck_maintenance.maintenance_date }}</div>
                            </template>
                        </div>
                    </div>
                    <div v-bind:class="{ 'form-group row':true, 'is-invalid':errors.has('plate_number') }">
                        <label for="inputPlateNumber" class="col-2 col-form-label">@lang('truck_maintenance.fields.plate_number')</label>
                        <div class="col-md-10">
                            <template v-if="mode == 'create' || mode == 'edit'">
                                <select class="form-control" id="inputPlateNumber" name="plate_number" v-model="truckMaintenance.plate_number" v-validate="'required'" data-vv-as="{{ trans('truck_maintenance.fields.plate_number') }}">
                                    <option v-bind:value="defaultPleaseSelect">@lang('labels.PLEASE_SELECT')</option>
                                    <option v-for="(s, sIdx) in plateNumberDDL" v-bind:value="s.code">@{{ s.description }}</option>
                                </select>
                                <div v-show="errors.has('plate_number')" class="invalid-feedback">@{{ errors.first('plate_number') }}</div>
                            </template>
                            <template v-if="mode == 'show'">
                                <div class="form-control-plaintext">@{{ truck_maintenance.plate_number }}</div>
                            </template>
                        </div>
                    </div>
                    <div v-bind:class="{ 'form-group row':true, 'is-invalid':errors.has('maintenance_type') }">
                        <label for="inputMaintenanceType" class="col-2 col-form-label">@lang('truck_maintenance.fields.maintenance_type')</label>
                        <div class="col-md-10">
                            <template v-if="mode == 'create' || mode == 'edit'">
								<select class="form-control" id="inputMaintenanceType" name="maintenance_type" v-model="truckMaintenance.maintenance_type" v-validate="'required'" data-vv-as="{{ trans('truck_maintenance.fields.maintenance_type') }}">
                                    <option v-bind:value="defaultPleaseSelect">@lang('labels.PLEASE_SELECT')</option>
                                    <option v-for="(s, sIdx) in maintenanceTypeDDL" v-bind:value="s.code">@{{ s.description }}</option>
                                </select>
                                <div v-show="errors.has('maintenance_type')" class="invalid-feedback">@{{ errors.first('maintenance_type') }}</div>
                            </template>
                            <template v-if="mode == 'show'">
                                <div class="form-control-plaintext">@{{ truck_maintenance.maintenance_type }}</div>
                            </template>
                        </div>
                    </div>
                    <div v-bind:class="{ 'form-group row':true, 'is-invalid':errors.has('cost') }">
                        <label for="inputCost" class="col-2 col-form-label">@lang('truck_maintenance.fields.cost')</label>
                        <div class="col-md-10">
                            <template v-if="mode == 'create' || mode == 'edit'">
                            <vue-autonumeric id="inputCost" type="text" name="cost" class="form-control" placeholder="@lang('truck_maintenance.fields.cost')" v-model="truckMaintenance.cost" v-validate="'required'" data-vv-as="{{ trans('truck_maintenance.fields.cost') }}" v-bind:options="{ digitGroupSeparator: '{{ Auth::user()->company->thousand_separator }}',decimalCharacter: '{{ Auth::user()->company->decimal_separator }}',decimalPlaces: '{{ Auth::user()->company->decimal_digit }}',minimumValue: '0',emptyInputBehavior: 'null' }"></vue-autonumeric>
                                <div v-show="errors.has('cost')" class="invalid-feedback">@{{ errors.first('cost') }}</div>
                            </template>
                            <template v-if="mode == 'show'">
                                <div class="form-control-plaintext">@{{ truck_maintenance.cost }}</div>
                            </template>
                        </div>
                    </div>
                    <div v-bind:class="{ 'form-group row':true, 'is-invalid':errors.has('odometer') }">
                        <label for="inputOdometer" class="col-2 col-form-label">@lang('truck_maintenance.fields.odometer')</label>
                        <div class="col-md-10">
                            <template v-if="mode == 'create' || mode == 'edit'">
                            	<vue-autonumeric id="inputOdometer" name="odometer" type="text" class="form-control" placeholder="@lang('truck_maintenance.fields.odometer')" value="" v-model="truckMaintenance.odometer" v-validate="'required'" data-vv-as="{{ trans('truck_maintenance.fields.odometer') }}" v-bind:options="{ digitGroupSeparator: '{{ Auth::user()->company->thousand_separator }}',decimalCharacter: '{{ Auth::user()->company->decimal_separator }}',decimalPlaces: 0,minimumValue: '0',emptyInputBehavior: 'null' }"></vue-autonumeric>
                                <div v-show="errors.has('odometer')" class="invalid-feedback">@{{ errors.first('odometer') }}</div>
                            </template>
                            <template v-if="mode == 'show'">
                                <div class="form-control-plaintext">@{{ truck_maintenance.odometer }}</div>
                            </template>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-2 col-form-label" for="inputRemarks">@lang('truck_maintenance.fields.remarks')</label>
                        <div class="col-md-10">
                            <template v-if="mode == 'create' || mode == 'edit'">
                                <input type="text" class="form-control" id="inputRemarks" name="remarks" placeholder="@lang('truck_maintenance.fields.remarks')"
                                       v-model="truckMaintenance.remarks">
                            </template>
                            <template v-if="mode == 'show'">
                                <div class="form-control-plaintext">@{{ truck_maintenance.remarks }}</div>
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
    @routes('truck_maintenance')
@endsection

@section('custom_js')
    <script type="application/javascript">

    </script>
    <script type="application/javascript" src="{{ mix('js/apps/truck_maintenance.js') }}"></script>
@endsection
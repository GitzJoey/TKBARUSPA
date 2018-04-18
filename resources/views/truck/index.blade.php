@extends('layouts.codebase.master')

@section('title')
    @lang('truck.index.title')
@endsection

@section('page_title')
    <span class="fa fa-truck fa-flip-horizontal fa-fw"></span>
    @lang('truck.index.page_title')
@endsection

@section('page_title_desc')
    @lang('truck.index.page_title_desc')
@endsection

@section('breadcrumbs')

@endsection

@section('content')
    <div id="truckVue">
        @include ('layouts.common.error')
        <div class="block block-shadow-on-hover block-mode-loading-refresh" id="truckListBlock">
            <div class="block-header block-header-default">
                <h3 class="block-title">@lang('truck.index.panel.list_panel.title')</h3>
                <div class="block-options">
                    <button type="button" class="btn-block-option" data-toggle="block-option" data-action="fullscreen_toggle"></button>
                    <button type="button" class="btn-block-option" v-on:click="getAllTruck">
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
                                <th class="text-center">@lang('truck.index.table.truck_list.header.type')</th>
                                <th class="text-center">@lang('truck.index.table.truck_list.header.plate_number')</th>
                                <th class="text-center">@lang('truck.index.table.truck_list.header.inspection_date')</th>
                                <th class="text-center">@lang('truck.index.table.truck_list.header.driver')</th>
                                <th class="text-center">@lang('truck.index.table.truck_list.header.status')</th>
                                <th class="text-center">@lang('truck.index.table.truck_list.header.remarks')</th>
                                <th class="text-center">@lang('labels.ACTION')</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(u, uIdx) in truckList">
                                <td>@{{ u.typeI18n }}</td>
                                <td>@{{ u.plate_number }}</td>
                                <td>@{{ u.inspection_date }}</td>
                                <td>@{{ u.driver }}</td>
                                <td>@{{ u.statusI18n }}</td>
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
        <div class="block block-shadow-on-hover block-mode-loading-refresh" id="truckCRUDBlock">
            <div class="block-header block-header-default">
                <h3 class="block-title">
                    <template v-if="mode == 'create'">@lang('truck.index.panel.crud_panel.title_create')</template>
                    <template v-if="mode == 'show'">@lang('truck.index.panel.crud_panel.title_show')</template>
                    <template v-if="mode == 'edit'">@lang('truck.index.panel.crud_panel.title_edit')</template>
                </h3>
                <div class="block-options">
                    <button type="button" class="btn-block-option" data-toggle="block-option" data-action="fullscreen_toggle"></button>
                    <button type="button" class="btn-block-option" data-toggle="block-option" data-action="content_toggle"></button>
                </div>
            </div>
            <div class="block-content">
                <form id="truckForm" method="post" v-on:submit.prevent="validateBeforeSubmit">
                    <input type="hidden" v-model="truck.hId" name="hId" value=""/>
                        <div v-bind:class="{ 'form-group row':true, 'is-invalid':errors.has('truck_type') }">
                        <label for="inputTruckType" class="col-2 col-form-label">@lang('truck.fields.type')</label>
                        <div class="col-md-10">
                            <template v-if="mode == 'create' || mode == 'edit'">
                                <select class="form-control" id="inputTruckType" name="truck_type"
                                        v-model="truck.type"
                                        v-validate="'required'"
                                        data-vv-as="{{ trans('truck.fields.type') }}">
                                    <option v-bind:value="defaultTruckType">@lang('labels.PLEASE_SELECT')</option>
                                    <option v-for="(s, sIdx) in truckTypeDDL" v-bind:value="s.code">@{{ s.description }}</option>
                                </select>
                                <div v-show="errors.has('truck_type')" class="invalid-feedback">@{{ errors.first('truck_type') }}</div>
                            </template>
                            <template v-if="mode == 'show'">
                                <div class="form-control-plaintext">@{{ truck.typeI18n }}</div>
                            </template>
                        </div>
                    </div>
                    <div v-bind:class="{ 'form-group row':true, 'is-invalid':errors.has('plate_number') }">
                        <label for="inputPlateNumber" class="col-2 col-form-label">@lang('truck.fields.plate_number')</label>
                        <div class="col-md-10">
                            <template v-if="mode == 'create' || mode == 'edit'">
                                <input id="inputPlateNumber" name="plate_number" type="text" class="form-control" placeholder="@lang('truck.fields.plate_number')"
                                v-model="truck.plate_number" v-validate="'required'" data-vv-as="{{ trans('truck.fields.plate_number') }}">
                                <div v-show="errors.has('plate_number')" class="invalid-feedback">@{{ errors.first('plate_number') }}</div>
                            </template>
                            <template v-if="mode == 'show'">
                                <div class="form-control-plaintext">@{{ truck.plate_number }}</div>
                            </template>
                        </div>
                    </div>
                    <div v-bind:class="{ 'form-group row':true, 'is-invalid':errors.has('inspection_date') }">
                        <label for="inputInspectionDate" class="col-2 col-form-label">@lang('truck.fields.inspection_date')</label>
                        <div class="col-md-10">
                            <template v-if="mode == 'create' || mode == 'edit'">
                                <div class="input-group">
                                    <flat-pickr name="inspection_date" v-model="truck.inspection_date" class="form-control" v-validate="'required'" data-vv-as="{{ trans('truck.fields.inspection_date') }}"></flat-pickr>
                                </div>
                            </template>
                            <template v-if="mode == 'show'">
                                <div class="form-control-plaintext">@{{ truck.inspection_date }}</div>
                            </template>
                        </div>
                    </div>
                    <div v-bind:class="{ 'form-group row':true, 'is-invalid':errors.has('driver') }">
                        <label for="inputDriver" class="col-2 col-form-label">@lang('truck.fields.driver')</label>
                        <div class="col-md-10">
                            <template v-if="mode == 'create' || mode == 'edit'">
                                <input id="inputDriver" name="driver" type="text" class="form-control" placeholder="@lang('truck.fields.driver')"
                                    v-model="truck.driver" v-validate="'required'" data-vv-as="{{ trans('truck.fields.driver') }}">
                                <div v-show="errors.has('driver')" class="invalid-feedback">@{{ errors.first('driver') }}</div>
                            </template>
                            <template v-if="mode == 'show'">
                                <div class="form-control-plaintext">@{{ truck.driver }}</div>
                            </template>
                        </div>
                    </div>
                    <div v-bind:class="{ 'form-group row':true, 'is-invalid':errors.has('status') }">
                        <label for="inputStatus" class="col-2 col-form-label">@lang('truck.fields.status')</label>
                        <div class="col-md-10">
                            <template v-if="mode == 'create' || mode == 'edit'">
                                <select class="form-control" id="inputStatus" name="status"
                                        v-model="truck.status" v-validate="'required'"
                                        data-vv-as="{{ trans('truck.fields.status') }}">
                                    <option v-bind:value="defaultStatus">@lang('labels.PLEASE_SELECT')</option>
                                    <option v-for="(s, sIdx) in statusDDL" v-bind:value="s.code">@{{ s.description }}</option>
                                </select>
                                <div v-show="errors.has('status')" class="invalid-feedback">@{{ errors.first('status') }}</div>
                            </template>
                            <template v-if="mode == 'show'">
                                <div class="form-control-plaintext">@{{ truck.statusI18n }}</div>
                            </template>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-2 col-form-label" for="inputRemarks">@lang('truck.fields.remarks')</label>
                        <div class="col-md-10">
                            <template v-if="mode == 'create' || mode == 'edit'">
                                <input type="text" class="form-control" id="inputRemarks" name="remarks" placeholder="@lang('truck.fields.remarks')"
                                       v-model="truck.remarks">
                            </template>
                            <template v-if="mode == 'show'">
                                <div class="form-control-plaintext">@{{ truck.remarks }}</div>
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
    @routes('truck')
@endsection

@section('custom_js')
    <script type="application/javascript">

    </script>
    <script type="application/javascript" src="{{ mix('js/apps/truck.min.js') }}"></script>
@endsection
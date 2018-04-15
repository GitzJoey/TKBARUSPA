@extends('layouts.codebase.master')

@section('title')
    @lang('warehouse.index.title')
@endsection

@section('page_title')
    <span class="fa fa-wrench fa-fw"></span>
    @lang('warehouse.index.page_title')
@endsection

@section('page_title_desc')
    @lang('warehouse.index.page_title_desc')
@endsection

@section('breadcrumbs')

@endsection

@section('content')
    <div id="warehouseVue">
        @include ('layouts.common.error')
        <div class="block block-shadow-on-hover block-mode-loading-refresh" id="warehouseListBlock">
            <div class="block-header block-header-default">
                <h3 class="block-title">@lang('warehouse.index.panel.list_panel.title')</h3>
                <div class="block-options">
                    <button type="button" class="btn-block-option" data-toggle="block-option" data-action="fullscreen_toggle"></button>
                    <button type="button" class="btn-block-option" v-on:click="getAllWarehouse">
                        <i class="si si-refresh"></i>
                    </button>
                    <button type="button" class="btn-block-option" data-toggle="block-option" data-action="content_toggle"></button>
                </div>
            </div>
            <div class="block-content">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-vcenter">
                        <thead class="thead-light">
                            <th class="text-center">@lang('warehouse.index.table.warehouse_list.header.name')</th>
                            <th class="text-center">@lang('warehouse.index.table.warehouse_list.header.address')</th>
                            <th class="text-center">@lang('warehouse.index.table.warehouse_list.header.phone_num')</th>
                            <th class="text-center">@lang('warehouse.index.table.warehouse_list.header.status')</th>
                            <th class="text-center">@lang('warehouse.index.table.warehouse_list.header.remarks')</th>
                            <th class="text-center action-column-width">@lang('labels.ACTION')</th>
                        </thead>
                        <tbody>
                            <tr v-for="(w, wIdx) in warehouseList">
                                <td>@{{ w.name }}</td>
                                <td>@{{ w.address }}</td>
                                <td>@{{ w.phone_num }}</td>
                                <td>@{{ w.statusI18n }}</td>
                                <td>@{{ w.remarks }}</td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <button class="btn btn-xs btn-secondary" v-on:click="showSelected(wIdx)"><span class="fa fa-info fa-fw"></span></button>
                                        <button class="btn btn-xs btn-secondary" v-on:click="editSelected(wIdx)"><span class="fa fa-pencil fa-fw"></span></button>
                                        <button class="btn btn-xs btn-secondary" v-on:click="deleteSelected(w.hId)"><span class="fa fa-close fa-fw"></span></button>
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
        <div class="block block-shadow-on-hover block-mode-loading-refresh" id="warehouseCRUDBlock">
            <div class="block-header block-header-default">
                <h3 class="block-title">
                    <template v-if="mode == 'create'">@lang('warehouse.index.panel.crud_panel.title_create')</template>
                    <template v-if="mode == 'show'">@lang('warehouse.index.panel.crud_panel.title_show')</template>
                    <template v-if="mode == 'edit'">@lang('warehouse.index.panel.crud_panel.title_edit')</template>
                </h3>
                <div class="block-options">
                    <button type="button" class="btn-block-option" data-toggle="block-option" data-action="fullscreen_toggle"></button>
                    <button type="button" class="btn-block-option" data-toggle="block-option" data-action="content_toggle"></button>
                </div>
            </div>
            <div class="block-content">
                <form id="warehouseForm" method="post" v-on:submit.prevent="validateBeforeSubmit">
                    <div v-bind:class="{ 'form-group row':true, 'is-invalid':errors.has('name') }">
                        <label for="inputName" class="col-2 col-form-label">@lang('warehouse.fields.name')</label>
                        <div class="col-md-10">
                            <template v-if="mode == 'create' || mode == 'edit'">
                                <input id="inputName" name="name" type="text" class="form-control" placeholder="@lang('warehouse.fields.name')"
                                       v-model="warehouse.name" v-validate="'required'" data-vv-as="{{ trans('warehouse.fields.name') }}">
                                <span v-show="errors.has('name')" class="invalid-feedback" v-cloak>@{{ errors.first('name') }}</span>
                            </template>
                            <template v-if="mode == 'show'">
                                <div class="form-control-plaintext">@{{ warehouse.name }}</div>
                            </template>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputAddress" class="col-2 col-form-label">@lang('warehouse.fields.address')</label>
                        <div class="col-md-10">
                            <template v-if="mode == 'create' || mode == 'edit'">
                                <input type="text" class="form-control" id="inputAddress" name="address" v-model="warehouse.address" placeholder="@lang('warehouse.fields.address')">
                            </template>
                            <template v-if="mode == 'show'">
                                <div class="form-control-plaintext">@{{ warehouse.address }}</div>
                            </template>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputPhoneNum" class="col-2 col-form-label">@lang('warehouse.fields.phone_num')</label>
                        <div class="col-md-10">
                            <template v-if="mode == 'create' || mode == 'edit'">
                                <input type="text" class="form-control" id="inputPhoneNum" name="phone_num" v-model="warehouse.phone_num" placeholder="@lang('warehouse.fields.phone_num')">
                            </template>
                            <template v-if="mode == 'show'">
                                <div class="form-control-plaintext">@{{ warehouse.phone_num }}</div>
                            </template>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputSection" class="col-2 col-form-label">@lang('warehouse.fields.section')</label>
                        <div class="col-md-10">
                            <table class="table table-bordered">
                                <thead class="thead-light">
                                    <tr>
                                        <th>@lang('warehouse.index.table.section_table.header.name')</th>
                                        <th>@lang('warehouse.index.table.section_table.header.position')</th>
                                        <th>@lang('warehouse.index.table.section_table.header.capacity')</th>
                                        <th>@lang('warehouse.index.table.section_table.header.capacity_unit')</th>
                                        <th>@lang('warehouse.index.table.section_table.header.remarks')</th>
                                        <th class="text-center">@lang('labels.ACTION')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(c, cI) in warehouse.sections">
                                        <td v-bind:class="{ 'is-invalid':errors.has('secname_' + cI) }">
                                            <template v-if="mode == 'create' || mode == 'edit'">
                                                <input type="text" class="form-control" v-model="c.name" name="section_name[]"
                                                       v-validate="'required'" v-bind:data-vv-as="'{{ trans('warehouse.index.table.section_table.header.name') }} ' + (cI + 1)" v-bind:data-vv-name="'secname_' + cI"/>
                                            </template>
                                            <template v-if="mode == 'show'">
                                                <div class="form-control-plaintext">@{{ c.name }}</div>
                                            </template>
                                        </td>
                                        <td v-bind:class="{ 'is-invalid':errors.has('secpos_' + cI) }">
                                            <template v-if="mode == 'create' || mode == 'edit'">
                                                <input type="text" class="form-control" v-model="c.position" name="section_position[]"
                                                       v-validate="'required'" v-bind:data-vv-as="'{{ trans('warehouse.index.table.section_table.header.position') }} ' + (cI + 1)" v-bind:data-vv-name="'secpos_' + cI"/>
                                            </template>
                                            <template v-if="mode == 'show'">
                                                <div class="form-control-plaintext">@{{ c.position }}</div>
                                            </template>
                                        </td>
                                        <td v-bind:class="{ 'is-invalid':errors.has('seccap_' + cI) }">
                                            <template v-if="mode == 'create' || mode == 'edit'">
                                                <input type="text" class="form-control text-right" name="section_capacity[]" v-model="c.capacity"
                                                       v-validate="'required|numeric'" v-bind:data-vv-as="'{{ trans('warehouse.index.table.section_table.header.capacity') }} ' + (cI + 1)" v-bind:data-vv-name="'seccap_' + cI"/>
                                            </template>
                                            <template v-if="mode == 'show'">
                                                <div class="form-control-plaintext">@{{ c.capacity }}</div>
                                            </template>
                                        </td>
                                        <td v-bind:class="{ 'is-invalid':errors.has('seccapunit_' + cI) }">
                                            <template v-if="mode == 'create' || mode == 'edit'">
                                                <select class="form-control"
                                                        name="section_capacity_unit_id[]"
                                                        v-model="c.capacityUnitHId"
                                                        v-validate="'required'"
                                                        v-bind:data-vv-as="'{{ trans('warehouse.index.table.section_table.header.capacity_unit') }} ' + (cI + 1)"
                                                        v-bind:data-vv-name="'seccapunit_' + cI">
                                                    <option value="">@lang('labels.PLEASE_SELECT')</option>
                                                    <option v-for="(u, uIdx) in unitDDL" v-bind:value="u.hId">@{{ u.unitName }}</option>
                                                </select>
                                            </template>
                                            <template v-if="mode == 'show'">
                                                <div class="form-control-plaintext">@{{ c.capacity_unit.unitName }}</div>
                                            </template>
                                        </td>
                                        <td>
                                            <template v-if="mode == 'create' || mode == 'edit'">
                                                <input type="text" class="form-control" v-model="c.remarks" name="section_remarks[]"/>
                                            </template>
                                            <template v-if="mode == 'show'">
                                                <div class="form-control-plaintext">@{{ c.remarks }}</div>
                                            </template>
                                        </td>
                                        <td class="text-center">
                                            <template v-if="mode == 'create' || mode == 'edit'">
                                                <button type="button" class="btn btn-xs btn-danger" v-bind:data="cI" v-on:click="removeSections(cI)">
                                                    <span class="fa fa-close fa-fw"></span>
                                                </button>
                                            </template>
                                            <template v-if="mode == 'show'"></template>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <template v-if="mode == 'create' || mode == 'edit'">
                                <button type="button" class="btn btn-xs btn-default" v-on:click="addSections">@lang('buttons.create_new_button')</button>
                            </template>
                            <template v-if="mode == 'show'"></template>
                        </div>
                    </div>
                    <div v-bind:class="{ 'form-group row':true, 'is-invalid':errors.has('status') }">
                        <label for="inputStatus" class="col-2 col-form-label">@lang('warehouse.fields.status')</label>
                        <div class="col-md-10">
                            <template v-if="mode == 'create' || mode == 'edit'">
                                <select class="form-control"
                                        name="status"
                                        v-model="warehouse.status"
                                        v-validate="'required'"
                                        data-vv-as="{{ trans('warehouse.fields.status') }}">
                                    <option v-bind:value="defaultPleaseSelect">@lang('labels.PLEASE_SELECT')</option>
                                    <option v-for="(s, sIdx) in statusDDL" v-bind:value="s.code">@{{ s.description }}</option>
                                </select>
                                <span v-show="errors.has('status')" class="invalid-feedback" v-cloak>@{{ errors.first('status') }}</span>
                            </template>
                            <template v-if="mode == 'show'">
                                <div class="form-control-plaintext">@{{ warehouse.statusI18n }}</div>
                            </template>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputRemarks" class="col-2 col-form-label">@lang('warehouse.fields.remarks')</label>
                        <div class="col-md-10">
                            <template v-if="mode == 'create' || mode == 'edit'">
                                <input type="text" class="form-control" id="inputRemarks" name="remarks" v-model="warehouse.remarks" placeholder="@lang('warehouse.fields.remarks')">
                            </template>
                            <template v-if="mode == 'show'">
                                <div class="form-control-plaintext">@{{ warehouse.remarks }}</div>
                            </template>
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

@section('custom_js')
    <script type="application/javascript">

    </script>
    <script type="application/javascript" src="{{ mix('js/apps/warehouse.js') }}"></script>
@endsection
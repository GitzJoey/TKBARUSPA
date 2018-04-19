@extends('layouts.codebase.master')

@section('title')
	@lang('role.index.title')
@endsection

@section('page_title')
	<span class="fa fa-key fa-fw"></span>
	@lang('role.index.page_title')
@endsection

@section('page_title_desc')
	@lang('role.index.page_title_desc')
@endsection

@section('breadcrumbs')

@endsection

@section('content')
    <div id="roleVue">
        @include ('layouts.common.error')
        <div class="block block-shadow-on-hover block-mode-loading-refresh" id="roleListBlock">
            <div class="block-header block-header-default">
                <h3 class="block-title">@lang('role.index.panel.list_panel.title')</h3>
                <div class="block-options">
                    <button type="button" class="btn-block-option" data-toggle="block-option" data-action="fullscreen_toggle"></button>
                    <button type="button" class="btn-block-option" v-on:click="getAllRole">
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
			                    <th class="text-center">@lang('role.index.table.role_list.header.name')</th>
			                    <th class="text-center">@lang('role.index.table.role_list.header.description')</th>
			                    <th class="text-center">@lang('role.index.table.role_list.header.permission')</th>
			                    <th class="text-center action-column-width">@lang('labels.ACTION')</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(u, uIdx) in roleList">
                                <td>@{{ u.name }}</td>
                                <td>@{{ u.description }}</td>
                                <td></td>
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
        <div class="block block-shadow-on-hover block-mode-loading-refresh" id="roleCRUDBlock">
            <div class="block-header block-header-default">
                <h3 class="block-title">
                    <template v-if="mode == 'create'">@lang('role.index.panel.crud_panel.title_create')</template>
                    <template v-if="mode == 'show'">@lang('role.index.panel.crud_panel.title_show')</template>
                    <template v-if="mode == 'edit'">@lang('role.index.panel.crud_panel.title_edit')</template>
                </h3>
                <div class="block-options">
                    <button type="button" class="btn-block-option" data-toggle="block-option" data-action="fullscreen_toggle"></button>
                    <button type="button" class="btn-block-option" data-toggle="block-option" data-action="content_toggle"></button>
                </div>
            </div>
            <div class="block-content">
                <form id="roleForm" method="post" v-on:submit.prevent="validateBeforeSubmit">
                    <input type="hidden" v-model="role.hId" name="hId" value=""/>
                    	<div v-bind:class="{ 'form-group row':true, 'is-invalid':errors.has('name') }">
                        <label for="inputName" class="col-2 col-form-label">@lang('role.fields.name')</label>
                        <div class="col-md-10">
                            <template v-if="mode == 'create' || mode == 'edit'">
                                <input id="inputName" name="name" type="text" class="form-control" placeholder="@lang('role.fields.name')"
                                v-model="role.name" v-validate="'required'" data-vv-as="{{ trans('role.fields.name') }}">
                                <div v-show="errors.has('name')" class="invalid-feedback">@{{ errors.first('name') }}</div>
                            </template>
                            <template v-if="mode == 'show'">
                                <div class="form-control-plaintext">@{{ role.name }}</div>
                            </template>
                        </div>
                    </div>
                    <div v-bind:class="{ 'form-group row':true, 'is-invalid':errors.has('display_name') }">
                        <label for="inputDisplayName" class="col-2 col-form-label">@lang('role.fields.display_name')</label>
                        <div class="col-md-10">
                            <template v-if="mode == 'create' || mode == 'edit'">
                                <input id="inputDisplayName" name="display_name" type="text" class="form-control" placeholder="@lang('role.fields.display_name')"
                                v-model="role.display_name" v-validate="'required'" data-vv-as="{{ trans('role.fields.display_name') }}">
                                <div v-show="errors.has('display_name')" class="invalid-feedback">@{{ errors.first('display_name') }}</div>
                            </template>
                            <template v-if="mode == 'show'">
                                <div class="form-control-plaintext">@{{ role.display_name }}</div>
                            </template>
                        </div>
                    </div>
                    <div v-bind:class="{ 'form-group row':true, 'is-invalid':errors.has('description') }">
                        <label for="inputDescription" class="col-2 col-form-label">@lang('role.fields.description')</label>
                        <div class="col-md-10">
                            <template v-if="mode == 'create' || mode == 'edit'">
                                <input id="inputDescription" name="description" type="text" class="form-control" placeholder="@lang('role.fields.description')"
                                    v-model="role.description" v-validate="'required'" data-vv-as="{{ trans('role.fields.description') }}">
                                <div v-show="errors.has('description')" class="invalid-feedback">@{{ errors.first('description') }}</div>
                            </template>
                            <template v-if="mode == 'show'">
                                <div class="form-control-plaintext">@{{ role.description }}</div>
                            </template>
                        </div>
                    </div>
                        <div v-bind:class="{ 'form-group row':true, 'is-invalid':errors.has('permission') }">
                        <label for="inputPermission" class="col-2 col-form-label">@lang('role.fields.permission')</label>
                        <div class="col-md-10">
                            <template v-if="mode == 'create' || mode == 'edit'">
                                <select multiple class="form-control" id="inputPermission" name="permission[]" size="25"
                                        v-model="role.permission"
                                        v-validate="'required'"
                                        data-vv-as="{{ trans('role.fields.permission') }}">
                                    <option v-bind:value="defaultPleaseSelect">@lang('labels.PLEASE_SELECT')</option>
                                    <option v-for="(p, pIdx) in permissionDDL" v-bind:value="p.id">@{{ p.display_name }}</option>
                                </select>
                                <div v-show="errors.has('permission')" class="invalid-feedback">@{{ errors.first('permission') }}</div>
                            </template>
                            <template v-if="mode == 'show'">
                                <div class="form-control-plaintext">@{{ role.permission }}</div>
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
    @routes('role')
@endsection

@section('custom_js')
    <script type="application/javascript">

    </script>
    <script type="application/javascript" src="{{ mix('js/apps/role.js') }}"></script>
@endsection
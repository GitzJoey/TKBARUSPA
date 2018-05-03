@extends('layouts.codebase.master')

@section('title')
	@lang('product_type.index.title')
@endsection

@section('page_title')
	<span class="fa fa-cube fa-fw"></span>@lang('product_type.index.page_title')
@endsection

@section('page_title_desc')
	@lang('product_type.index.page_title_desc')
@endsection

@section('breadcrumbs')

@endsection

@section('content')
    <div id="productTypeVue">
        @include ('layouts.common.error')
        <div class="block block-shadow-on-hover block-mode-loading-refresh" id="productTypeListBlock">
            <div class="block-header block-header-default">
                <h3 class="block-title">@lang('product_type.index.panel.list_panel.title')</h3>
                <div class="block-options">
                    <button type="button" class="btn-block-option" data-toggle="block-option" data-action="fullscreen_toggle"></button>
                    <button type="button" class="btn-block-option" v-on:click="getAllProductType">
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
		                        <th class="text-center">@lang('product_type.index.table.product_type_list.header.name')</th>
		                        <th class="text-center">@lang('product_type.index.table.product_type_list.header.short_code')</th>
		                        <th class="text-center">@lang('product_type.index.table.product_type_list.header.description')</th>
		                        <th class="text-center">@lang('product_type.index.table.product_type_list.header.status')</th>
		                        <th class="text-center">@lang('labels.ACTION')</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(pt, ptIdx) in productTypeList">
                                <td>@{{ pt.name }}</td>
                                <td>@{{ pt.short_code }}</td>
                                <td>@{{ pt.description }}</td>
                                <td>@{{ pt.statusI18n }}</td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <button class="btn btn-sm btn-secondary" v-on:click="showSelected(ptIdx)"><span class="fa fa-info fa-fw"></span></button>
                                        <button class="btn btn-sm btn-secondary" v-on:click="editSelected(ptIdx)"><span class="fa fa-pencil fa-fw"></span></button>
                                        <button class="btn btn-sm btn-secondary" v-on:click="deleteSelected(pt.hId)"><span class="fa fa-close fa-fw"></span></button>
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
        <div class="block block-shadow-on-hover block-mode-loading-refresh" id="productTypeCRUDBlock">
            <div class="block-header block-header-default">
                <h3 class="block-title">
                    <template v-if="mode == 'create'">@lang('product_type.index.panel.crud_panel.title_create')</template>
                    <template v-if="mode == 'show'">@lang('product_type.index.panel.crud_panel.title_show')</template>
                    <template v-if="mode == 'edit'">@lang('product_type.index.panel.crud_panel.title_edit')</template>
                </h3>
                <div class="block-options">
                    <button type="button" class="btn-block-option" data-toggle="block-option" data-action="fullscreen_toggle"></button>
                    <button type="button" class="btn-block-option" data-toggle="block-option" data-action="content_toggle"></button>
                </div>
            </div>
            <div class="block-content">
                <form id="productTypeForm" method="post" v-on:submit.prevent="validateBeforeSubmit">
                    <input type="hidden" v-model="productType.hId" name="hId" value=""/>
                        <div v-bind:class="{ 'form-group row':true, 'is-invalid':errors.has('name') }">
                        <label for="inputName" class="col-2 col-form-label">@lang('product_type.fields.name')</label>
                        <div class="col-md-10">
                            <template v-if="mode == 'create' || mode == 'edit'">
								<input id="inputName" name="name" type="text" class="form-control" placeholder="@lang('product_type.fields.name')"
                                v-model="productType.name" v-validate="'required'" data-vv-as="{{ trans('product_type.fields.name') }}">
                                <div v-show="errors.has('name')" class="invalid-feedback">@{{ errors.first('name') }}</div>
                            </template>
                            <template v-if="mode == 'show'">
                                <div class="form-control-plaintext">@{{ productType.name }}</div>
                            </template>
                        </div>
                    </div>
                    <div v-bind:class="{ 'form-group row':true, 'is-invalid':errors.has('short_code') }">
                        <label for="inputShortCode" class="col-2 col-form-label">@lang('product_type.fields.short_code')</label>
                        <div class="col-md-10">
                            <template v-if="mode == 'create' || mode == 'edit'">
								<input id="inputShortCode" name="name" type="text" class="form-control" placeholder="@lang('product_type.fields.short_code')"
                                v-model="productType.short_code" v-validate="'required'" data-vv-as="{{ trans('product_type.fields.short_code') }}">
                                <div v-show="errors.has('short_code')" class="invalid-feedback">@{{ errors.first('short_code') }}</div>
                            </template>
                            <template v-if="mode == 'show'">
                                <div class="form-control-plaintext">@{{ productType.short_code }}</div>
                            </template>
                        </div>
                    </div>
                    <div v-bind:class="{ 'form-group row':true, 'is-invalid':errors.has('description') }">
                        <label for="inputDescription" class="col-2 col-form-label">@lang('product_type.fields.description')</label>
                        <div class="col-md-10">
                            <template v-if="mode == 'create' || mode == 'edit'">
								<input id="inputDescription" name="name" type="text" class="form-control" placeholder="@lang('product_type.fields.description')"
                                v-model="productType.description" v-validate="'required'" data-vv-as="{{ trans('product_type.fields.description') }}">
                                <div v-show="errors.has('description')" class="invalid-feedback">@{{ errors.first('description') }}</div>
                            </template>
                            <template v-if="mode == 'show'">
                                <div class="form-control-plaintext">@{{ productType.description }}</div>
                            </template>
                        </div>
                    </div>
                    <div v-bind:class="{ 'form-group row':true, 'is-invalid':errors.has('status') }">
                        <label for="inputStatus" class="col-2 col-form-label">@lang('product_type.fields.status')</label>
                        <div class="col-md-10">
                            <template v-if="mode == 'create' || mode == 'edit'">
                                <select class="form-control" id="inputStatus" name="status"
                                        v-model="productType.status" v-validate="'required'"
                                        data-vv-as="{{ trans('product_type.fields.status') }}">
                                    <option v-bind:value="defaultPleaseSelect">@lang('labels.PLEASE_SELECT')</option>
                                    <option v-for="(s, sIdx) in statusDDL" v-bind:value="s.code">@{{ s.description }}</option>
                                </select>
                                <div v-show="errors.has('status')" class="invalid-feedback">@{{ errors.first('status') }}</div>
                            </template>
                            <template v-if="mode == 'show'">
                                <div class="form-control-plaintext">@{{ productType.statusI18n }}</div>
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
    @routes('product_type')
@endsection

@section('custom_js')
    <script type="application/javascript">

    </script>
    <script type="application/javascript" src="{{ mix('js/apps/product_type.js') }}"></script>
@endsection
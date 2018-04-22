@extends('layouts.codebase.master')

@section('title')
    @lang('phone_provider.index.title')
@endsection

@section('page_title')
    <span class="fa fa-bolt fa-fw"></span>
    @lang('phone_provider.index.page_title')
@endsection

@section('page_title_desc')
    @lang('phone_provider.index.page_title_desc')
@endsection

@section('breadcrumbs')

@endsection

@section('content')
    <div id="phoneProviderVue">
        @include ('layouts.common.error')
        <div class="block block-shadow-on-hover block-mode-loading-refresh" id="phoneProviderListBlock">
			<div class="block-header block-header-default">
                <h3 class="block-title">@lang('phone_provider.index.panel.list_panel.title')</h3>
                <div class="block-options">
                    <button type="button" class="btn-block-option" data-toggle="block-option" data-action="fullscreen_toggle"></button>
                    <button type="button" class="btn-block-option" v-on:click="">
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
                                <th class="text-center">@lang('phone_provider.index.table.phone_provider_list.header.name')</th>
                                <th class="text-center">@lang('phone_provider.index.table.phone_provider_list.header.short_name')</th>
                                <th class="text-center">@lang('phone_provider.index.table.phone_provider_list.header.prefix')</th>
                                <th class="text-center">@lang('phone_provider.index.table.phone_provider_list.header.status')</th>
                                <th class="text-center">@lang('phone_provider.index.table.phone_provider_list.header.remarks')</th>
                                <th class="text-center action-column-width">@lang('labels.ACTION')</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(u, uIdx) in phoneProviderList">
                                <td>@{{ u.name }}</td>
                                <td>@{{ u.short_name }}</td>
                                <td class="text-center">
                                    <div v-for="(a, prefixId) in u.prefixes">
                                        @{{ a.prefix }}
                                    </div>
                                </td>
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
        <div class="block block-shadow-on-hover block-mode-loading-refresh" id="phoneProviderCRUDBlock">
            <div class="block-header block-header-default">
                <h3 class="block-title">
                    <template v-if="mode == 'create'">@lang('phone_provider.index.panel.crud_panel.title_create')</template>
                    <template v-if="mode == 'show'">@lang('phone_provider.index.panel.crud_panel.title_show')</template>
                    <template v-if="mode == 'edit'">@lang('phone_provider.index.panel.crud_panel.title_edit')</template>
                </h3>
                <div class="block-options">
                    <button type="button" class="btn-block-option" data-toggle="block-option" data-action="fullscreen_toggle"></button>
                    <button type="button" class="btn-block-option" data-toggle="block-option" data-action="content_toggle"></button>
                </div>
            </div>
            <div class="block-content">
				<form id="phoneProviderForm" method="post" v-on:submit.prevent="validateBeforeSubmit">
                    <input type="hidden" v-model="phone_provider.hId" name="hId" value=""/>
                    <div v-bind:class="{ 'form-group':true, 'row':true, 'is-invalid':errors.has('name') }">
                        <label class="col-2 col-form-label" for="inputName">@lang('phone_provider.index.fields.name')</label>
                        <div class="col-md-10">
                            <template v-if="mode == 'create' || mode == 'edit'">
                                <input type="text" class="form-control" id="inputName" name="name" placeholder="@lang('phone_provider.index.fields.name')"
                                       v-model="phone_provider.name"
                                       v-validate="'required'">
                                <div v-show="errors.has('name')" class="invalid-feedback">@{{ errors.first('name') }}</div>
                            </template>
                            <template v-if="mode == 'show'">
                                <div class="form-control-plaintext">@{{ phone_provider.name }}</div>
                            </template>
                        </div>
                    </div>
                    <div v-bind:class="{ 'form-group':true, 'row':true, 'is-invalid':errors.has('short_name') }">
                        <label class="col-2 col-form-label" for="inputName">@lang('phone_provider.index.fields.short_name')</label>
                        <div class="col-md-10">
                            <template v-if="mode == 'create' || mode == 'edit'">
                                <input type="text" class="form-control" id="inputShortName" name="short_name" placeholder="@lang('phone_provider.index.fields.short_name')"
                                       v-model="phone_provider.short_name"
                                       v-validate="'required'">
                                <div v-show="errors.has('short_name')" class="invalid-feedback">@{{ errors.first('short_name') }}</div>
                            </template>
                            <template v-if="mode == 'show'">
                                <div class="form-control-plaintext">@{{ phone_provider.short_name }}</div>
                            </template>
                        </div>
                    </div>
                    <div v-bind:class="{ 'form-group':true, 'row':true, 'is-invalid':errors.has('short_name') }">
                        <label class="col-2 col-form-label" for="inputPrefix">@lang('phone_provider.index.fields.prefix')</label>
                        <div class="col-sm-5">
                            <table class="table table-striped table-bordered" v-if="mode == 'show'">
                                <thead>
                                    <tr>
                                        <th>@lang('phone_provider.index.table.prefix.header.prefix')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(a, aIdx) in phone_provider.prefixes">
                                        <td>@{{ a.prefix }}</td>
                                    </tr>
                                </tbody>
                            </table>
                            <table class="table table-striped table-bordered" v-if="mode == 'create' || mode == 'edit'">
                                <thead>
                                    <tr>
                                        <th>@lang('phone_provider.index.table.prefix.header.prefix')</th>
                                        <th class="text-center">@lang('labels.ACTION')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(p, pIdx) in prefixes">
                                        <td v-bind:class="{ 'is-invalid':errors.has('prefixes' + pIdx) }">
                                            <input type="hidden" name="level[]" v-bind:value="pIdx">
                                            <input type="text" class="form-control" v-model="p.prefix" name="prefixes[]"
                                                v-validate="'required'" v-bind:data-vv-as="'@lang('phone_provider.index.fields.prefix') ' + (pIdx + 1)"
                                                v-bind:data-vv-name="'prefixes' + pIdx">
                                        </td>
                                        <td class="text-center valign-middle">
                                            <button type="button" class="btn btn-sm btn-danger" v-show="prefixes.length" v-on:click="removePrefix(pIdx)">@lang('buttons.remove_button')</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <button type="button" class="btn btn-sm btn-primary" v-if="mode == 'create' || mode == 'edit'" v-on:click="addNewPrefix()">@lang('buttons.create_new_button')</button>
                        </div>
                    </div>
                    <div v-bind:class="{ 'form-group':true, 'row':true, 'is-invalid':errors.has('status') }">
                        <label class="col-2 col-form-label" for="inputName">@lang('phone_provider.index.fields.status')</label>
                        <div class="col-md-10">
                            <template v-if="mode == 'create' || mode == 'edit'">
                                <select class="form-control" id="inputStatus" name="status" v-model="phone_provider.status" v-validate="'required'">
                                    <option v-bind:value="defaultStatus">@lang('labels.PLEASE_SELECT')</option>
                                    <option v-for="(s, sIdx) in statusDDL" v-bind:value="s.code">@{{ s.description }}</option>
                                </select>
                                <div v-show="errors.has('status')" class="invalid-feedback">@{{ errors.first('status') }}</div>
                            </template>
                            <template v-if="mode == 'show'">
                                <div class="form-control-plaintext">@{{ phone_provider.statusI18n }}</div>
                            </template>
                        </div>
                    </div>
                    <div v-bind:class="{ 'form-group':true, 'row':true }">
                        <label class="col-2 col-form-label" for="inputRemarks">@lang('phone_provider.index.fields.remarks')</label>
                        <div class="col-md-10">
                            <template v-if="mode == 'create' || mode == 'edit'">
                                <input type="text" class="form-control" id="inputShortName" name="remarks" placeholder="@lang('phone_provider.index.fields.remarks')"
                                       v-model="phone_provider.remarks">
                                <div v-show="errors.has('remarks')" class="invalid-feedback">@{{ errors.first('remarks') }}</div>
                            </template>
                            <template v-if="mode == 'show'">
                                <div class="form-control-plaintext">@{{ phone_provider.remarks }}</div>
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
    @routes('phone_provider')
@endsection

@section('custom_js')
    <script type="application/javascript">

    </script>
    <script type="application/javascript" src="{{ asset('js/apps/phone_provider.min.js') }}"></script>
@endsection
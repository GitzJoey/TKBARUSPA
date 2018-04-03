@extends('layouts.codebase.master')

@section('title')
    @lang('unit.index.title')
@endsection

@section('page_title')
    @lang('unit.index.page_title')
@endsection

@section('page_title_desc')
    @lang('unit.index.page_title_desc')
@endsection

@section('breadcrumbs')

@endsection

@section('content')
    <div id="unitVue">
        <div class="alert alert-danger alert-dismissable" role="alert" v-show="errors.count() > 0">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <h3 class="alert-heading font-size-h4 font-w400">
                @lang('labels.GENERAL_ERROR_TITLE')
            </h3>
            <p>@lang('labels.GENERAL_ERROR_DESC')</p>
            <ul v-for="(e, eIdx) in errors.all()"><li>@{{ e }}</li></ul>
        </div>
        <div class="block block-shadow-on-hover block-mode-loading-refresh" id="unitListBlock">
            <div class="block-header block-header-default">
                <h3 class="block-title">@lang('unit.index.table.unit_list.title')</h3>
                <div class="block-options">
                    <button type="button" class="btn-block-option" data-toggle="block-option" data-action="fullscreen_toggle"></button>
                    <button type="button" class="btn-block-option" v-on:click="getAllUnit">
                        <i class="si si-refresh"></i>
                    </button>
                    <button type="button" class="btn-block-option" data-toggle="block-option" data-action="content_toggle"></button>
                </div>
            </div>
            <div class="block-content">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-vcenter">
                        <thead class="thead-default">
                            <th class="text-center">@lang('unit.index.table.unit_list.header.name')</th>
                            <th class="text-center">@lang('unit.index.table.unit_list.header.symbol')</th>
                            <th class="text-center">@lang('unit.index.table.unit_list.header.status')</th>
                            <th class="text-center">@lang('unit.index.table.unit_list.header.remarks')</th>
                            <th class="text-center action-column-width">@lang('labels.ACTION')</th>
                        </thead>
                        <tbody>
                            <tr v-for="(u, uIdx) in unitList">
                                <td>@{{ u.name }}</td>
                                <td>@{{ u.symbol }}</td>
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
        <div class="block block-shadow-on-hover block-mode-loading-energy" id="unitCRUDBlock">
            <div class="block-header block-header-default">
                <h3 class="block-title">@lang('unit.index.field.title')</h3>
                <div class="block-options">
                    <button type="button" class="btn-block-option" data-toggle="block-option" data-action="fullscreen_toggle"></button>
                    <button type="button" class="btn-block-option" data-toggle="block-option" data-action="content_toggle"></button>
                </div>
            </div>
            <div class="block-content">
                <form id="unitForm" method="post" v-on:submit.prevent="validateBeforeSubmit">
                    <input type="hidden" v-model="unit.hId" name="hId" value=""/>
                    <div v-bind:class="{ 'form-group':true, 'row':true, 'is-invalid':errors.has('name') }">
                        <label class="col-12" for="inputName">@lang('unit.index.field.name')</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" id="inputName" name="name" placeholder="@lang('unit.index.field.name')"
                                   v-model="unit.name"
                                   v-validate="'required'">
                            <div v-show="errors.has('name')" class="invalid-feedback">@{{ errors.first('name') }}</div>
                        </div>
                    </div>
                    <div v-bind:class="{ 'form-group':true, 'row':true, 'is-invalid':errors.has('symbol') }">
                        <label class="col-12" for="inputSymbol">@lang('unit.index.field.symbol')</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" id="inputSymbol" name="symbol" placeholder="@lang('unit.index.field.symbol')"
                                   v-model="unit.symbol"
                                   v-validate="'required'">
                            <div v-show="errors.has('symbol')" class="invalid-feedback">@{{ errors.first('symbol') }}</div>
                        </div>
                    </div>
                    <div v-bind:class="{ 'form-group':true, 'row':true, 'is-invalid':errors.has('status') }">
                        <label class="col-12" for="inputStatus">@lang('unit.index.field.status')</label>
                        <div class="col-md-9">
                            <select class="form-control" id="inputStatus" name="status" v-model="unit.status" v-validate="'required'">
                                <option v-bind:value="defaultStatus">@lang('labels.PLEASE_SELECT')</option>
                                <option v-for="(s, sIdx) in statusDDL" v-bind:value="s.code">@{{ s.description }}</option>
                            </select>
                            <div v-show="errors.has('status')" class="invalid-feedback">@{{ errors.first('status') }}</div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-12" for="inputRemarks">@lang('unit.index.field.remarks')</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" id="inputRemarks" name="remarks" placeholder="@lang('unit.index.field.remarks')"
                                   v-model="unit.remarks">
                        </div>
                    </div>
                    <div class="row items-push-2x text-center text-sm-left">
                        <div class="col-sm-6 col-xl-4">
                            <template v-if="mode == 'create' || mode == 'edit'">
                                <button type="submit" class="btn btn-primary min-width-125">
                                    @lang('buttons.submit_button')
                                </button>
                                <button type="button" class="btn btn-default min-width-125" v-on:click="backToList">
                                    @lang('buttons.cancel_button')
                                </button>
                            </template>
                            <template v-if="mode == 'show'">
                                <button type="button" class="btn btn-default min-width-125" v-on:click="backToList" v-show="mode == 'show'">
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
    <script type="application/javascript" src="{{ mix('js/apps/unit.min.js') }}"></script>
@endsection
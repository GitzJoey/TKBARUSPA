@extends('layouts.codebase.master')

@section('title')
    @lang('price_level.index.title')
@endsection

@section('page_title')
    @lang('price_level.index.page_title')
@endsection

@section('page_title_desc')
    @lang('price_level.index.page_title_desc')
@endsection

@section('breadcrumbs')

@endsection

@section('content')
    <div id="priceLevelVue">
        @include ('layouts.common.error')
        <div class="block block-shadow-on-hover block-mode-loading-refresh" id="priceLevelListBlock">
            <div class="block-header block-header-default">
                <h3 class="block-title">@lang('price_level.index.panel.list_panel.title')</h3>
                <div class="block-options">
                    <button type="button" class="btn-block-option" data-toggle="block-option" data-action="fullscreen_toggle"></button>
                    <button type="button" class="btn-block-option" v-on:click="getAllPriceLevel">
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
                                <th class="text-center">@lang('price_level.index.table.price_level_list.header.type')</th>
                                <th class="text-center">@lang('price_level.index.table.price_level_list.header.weight')</th>
                                <th class="text-center">@lang('price_level.index.table.price_level_list.header.name')</th>
                                <th class="text-center">@lang('price_level.index.table.price_level_list.header.description')</th>
                                <th class="text-center">@lang('price_level.index.table.price_level_list.header.value')</th>
                                <th class="text-center">@lang('price_level.index.table.price_level_list.header.status')</th>
                                <th class="text-center">@lang('labels.ACTION')</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(pl, plIdx) in priceLevelList" v-show="priceLevelList.length != 0">
                                <td>@{{ pl.typeI18n }}</td>
                                <td>@{{ pl.weight }}</td>
                                <td>@{{ pl.name }}</td>
                                <td>@{{ pl.description }}</td>
                                <td>@{{ getValue(pl) }}</td>
                                <td>@{{ pl.statusI18n }}</td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <button class="btn btn-sm btn-secondary" v-on:click="showSelected(plIdx)"><span class="fa fa-info fa-fw"></span></button>
                                        <button class="btn btn-sm btn-secondary" v-on:click="editSelected(plIdx)"><span class="fa fa-pencil fa-fw"></span></button>
                                        <button class="btn btn-sm btn-secondary" v-on:click="deleteSelected(pl.hId)"><span class="fa fa-close fa-fw"></span></button>
                                    </div>
                                </td>
                            </tr>
                            <tr v-show="priceLevelList.length == 0">
                                <td class="text-center" colspan="7">@lang('labels.DATA_NOT_FOUND')</td>
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
        <div class="block block-shadow-on-hover block-mode-loading-refresh" id="priceLevelCRUDBlock">
            <div class="block-header block-header-default">
                <h3 class="block-title">
                    <template v-if="mode == 'create'">@lang('price_level.index.panel.crud_panel.title_create')</template>
                    <template v-if="mode == 'show'">@lang('price_level.index.panel.crud_panel.title_show')</template>
                    <template v-if="mode == 'edit'">@lang('price_level.index.panel.crud_panel.title_edit')</template>
                </h3>
                <div class="block-options">
                    <button type="button" class="btn-block-option" data-toggle="block-option" data-action="fullscreen_toggle"></button>
                    <button type="button" class="btn-block-option" data-toggle="block-option" data-action="content_toggle"></button>
                </div>
            </div>
            <div class="block-content">
                <form id="priceLevelForm" method="post" v-on:submit.prevent="validateBeforeSubmit">
                    <div v-bind:class="{ 'form-group row':true, 'is-invalid':errors.has('type') }">
                        <label for="inputType" class="col-2 col-form-label">@lang('price_level.fields.type')</label>
                        <div class="col-md-10">
                            <template v-if="mode == 'create' || mode == 'edit'">
                                <select class="form-control"
                                        name="type"
                                        id="priceLevelSelect"
                                        v-model="priceLevel.type"
                                        v-validate="'required'"
                                        data-vv-as="{{ trans('price_level.fields.type') }}">
                                    <option v-bind:value="defaultPleaseSelect">@lang('labels.PLEASE_SELECT')</option>
                                    <option v-for="(pl, plIdx) in priceLevelTypeDDL" v-bind:value="pl.code">@{{ pl.description }}</option>
                                </select>
                                <span v-show="errors.has('type')" class="help-block">@{{ errors.first('type') }}</span>
                            </template>
                            <template v-if="mode == 'show'">
                                <div class="form-control-plaintext">@{{ priceLevel.type }}</div>
                            </template>
                        </div>
                    </div>
                    <div v-bind:class="{ 'form-group row':true, 'is-invalid':errors.has('weight') }">
                        <label for="inputWeight" class="col-2 col-form-label">@lang('price_level.fields.weight')</label>
                        <div class="col-md-10">
                            <template v-if="mode == 'create' || mode == 'edit'">
                                <select class="form-control"
                                        name="weight"
                                        v-model="priceLevel.weight"
                                        v-validate="'required'"
                                        data-vv-as="{{ trans('price_level.fields.weight') }}">
                                    <option v-bind:value="defaultPleaseSelect">@lang('labels.PLEASE_SELECT')</option>
                                    @for($x =1; $x <= 10; $x++)
                                        @if($x == 1)
                                            <option v-bind:value="{{ $x }}">{{ $x }} - Lowest</option>
                                        @elseif($x == 10)
                                            <option v-bind:value="{{ $x }}">{{ $x }} - Highest</option>
                                        @else
                                            <option v-bind:value="{{ $x }}">{{ $x }}</option>
                                        @endif
                                    @endfor
                                </select>
                                <span v-show="errors.has('weight')" class="help-block">@{{ errors.first('weight') }}</span>
                            </template>
                            <template v-if="mode == 'show'">
                                <div class="form-control-plaintext">@{{ priceLevel.weight }}</div>
                            </template>
                        </div>
                    </div>
                    <div v-bind:class="{ 'form-group row':true, 'is-invalid':errors.has('name') }">
                        <label for="inputName" class="col-2 col-form-label">@lang('price_level.fields.name')</label>
                        <div class="col-md-10">
                            <template v-if="mode == 'create' || mode == 'edit'">
                                <input id="inputName" name="name" type="text" class="form-control" placeholder="@lang('price_level.fields.name')"
                                       v-model="priceLevel.name" v-validate="'required'" data-vv-as="{{ trans('price_level.fields.name') }}">
                                <span v-show="errors.has('name')" class="help-block">@{{ errors.first('name') }}</span>
                            </template>
                            <template v-if="mode == 'show'">
                                <div class="form-control-plaintext">@{{ priceLevel.name }}</div>
                            </template>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputDescription" class="col-2 col-form-label">@lang('price_level.fields.description')</label>
                        <div class="col-md-10">
                            <template v-if="mode == 'create' || mode == 'edit'">
                                <input id="inputDescription" name="description" type="text" class="form-control" v-model="priceLevel.description" placeholder="Description">
                            </template>
                            <template v-if="mode == 'show'">
                                <div class="form-control-plaintext">@{{ priceLevel.description }}</div>
                            </template>
                        </div>
                    </div>
                    <div v-bind:class="{ 'form-group row':true, 'is-invalid':errors.has('increment_value') }">
                        <label for="inputIncVal" class="col-2 col-form-label">@lang('price_level.fields.incval')</label>
                        <div class="col-md-10">
                            <template v-if="mode == 'create' || mode == 'edit'">
                                <input id="inputIncVal" name="increment_value" type="text" class="form-control" placeholder="Increment Value" v-model="priceLevel.increment_value"
                                       v-bind:readonly="setIncReadOnly(priceLevel.type)" v-validate="setIncReadOnly(priceLevel.type) ? '':'required:numeric:2'" data-vv-as="{{ trans('price_level.fields.incval') }}">
                                <span v-show="errors.has('increment_value')" class="help-block">@{{ errors.first('increment_value') }}</span>
                            </template>
                            <template v-if="mode == 'show'">
                                <div class="form-control-plaintext">@{{ priceLevel.increment_val }}</div>
                            </template>
                        </div>
                    </div>
                    <div v-bind:class="{ 'form-group row':true, 'is-invalid':errors.has('percentage_value') }">
                        <label for="inputPctVal" class="col-2 col-form-label">@lang('price_level.fields.pctval')</label>
                        <div class="col-md-10">
                            <template v-if="mode == 'create' || mode == 'edit'">
                                <input id="inputPctVal" name="percentage_value" type="text" class="form-control" placeholder="Percentage Value" v-model="priceLevel.percentage_value"
                                       v-bind:readonly="setPctReadOnly(priceLevel.type)" v-validate="setPctReadOnly(priceLevel.type) ? '':'required:numeric:2'" data-vv-as="{{ trans('price_level.fields.pctval') }}">
                                <span v-show="errors.has('percentage_value')" class="invalid-feedback">@{{ errors.first('percentage_value') }}</span>
                            </template>
                            <template v-if="mode == 'show'">
                                <div class="form-control-plaintext">@{{ priceLevel.percentage_value }}</div>
                            </template>
                        </div>
                    </div>
                    <div v-bind:class="{ 'form-group row':true, 'is-invalid':errors.has('status') }">
                        <label for="inputStatus" class="col-2 col-form-label">@lang('price_level.fields.status')</label>
                        <div class="col-md-10">
                            <template v-if="mode == 'create' || mode == 'edit'">
                                <select class="form-control"
                                        name="status"
                                        v-model="priceLevel.status"
                                        v-validate="'required'"
                                        data-vv-as="{{ trans('price_level.fields.status') }}">
                                    <option v-bind:value="defaultPleaseSelect">@lang('labels.PLEASE_SELECT')</option>
                                    <option v-for="(s, sI) in statusDDL" v-bind:value="s.code">@{{ s.description }}</option>
                                </select>
                                <span v-show="errors.has('status')" class="invalid-feedback">@{{ errors.first('status') }}</span>
                            </template>
                            <template v-if="mode == 'show'">
                                <div class="form-control-plaintext">@{{ priceLevel.statusI18n }}</div>
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

@section('ziggy')
    @routes('price_level')
@endsection

@section('custom_js')
    <script type="application/javascript">

    </script>
    <script type="application/javascript" src="{{ mix('js/apps/price_level.js') }}"></script>
@endsection
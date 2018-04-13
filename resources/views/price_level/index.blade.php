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
                            <tr v-for="(pl, plIdx) in priceLevelList">
                                <td>@{{ pl.type }}</td>
                                <td>@{{ pl.weight }}</td>
                                <td>@{{ pl.name }}</td>
                                <td>@{{ pl.description }}</td>
                                <td>@{{ pl.value }}</td>
                                <td>@{{ pl.statusI18n }}</td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <button class="btn btn-sm btn-secondary" v-on:click="showSelected(pLIdx)"><span class="fa fa-info fa-fw"></span></button>
                                        <button class="btn btn-sm btn-secondary" v-on:click="editSelected(pLIdx)"><span class="fa fa-pencil fa-fw"></span></button>
                                        <button class="btn btn-sm btn-secondary" v-on:click="deleteSelected(pL.hId)"><span class="fa fa-close fa-fw"></span></button>
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
                </form>
            </div>
        </div>
    </div>
@endsection

@section('custom_js')
    <script type="application/javascript">
        var priceLevelVue = new Vue ({
            el: '#priceLevelVue',
            data: {
                priceLevelList: []
            },
            mounted: function () {

            },
            methods: {

            },
            function: {

            }
        });
    </script>
    <script type="application/javascript" src="{{ mix('js/apps/price_level.js') }}"></script>
@endsection
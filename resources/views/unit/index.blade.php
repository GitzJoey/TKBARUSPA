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
        <div class="block block-shadow-on-hover block-mode-loading-energy" id="unitListBlock">
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
                        <thead>
                            <th class="text-center">@lang('unit.index.table.unit_list.header.name')</th>
                            <th class="text-center">@lang('unit.index.table.unit_list.header.symbol')</th>
                            <th class="text-center">@lang('unit.index.table.unit_list.header.status')</th>
                            <th class="text-center">@lang('unit.index.table.unit_list.header.remarks')</th>
                            <th class="text-center">@lang('labels.ACTION')</th>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom_js')
    <script type="application/javascript">

    </script>
    <script type="application/javascript" sr="{{ mix('js/apps/unit.js') }}"/>
@endsection
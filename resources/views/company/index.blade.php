@extends('layouts.codebase.master')

@section('title')
    @lang('company.index.title')
@endsection

@section('page_title')
    @lang('company.index.page_title')
@endsection

@section('page_title_desc')
    @lang('company.index.page_title_desc')
@endsection

@section('breadcrumbs')

@endsection

@section('content')
    <div id="companyVue">
        <div class="block block-shadow-on-hover">
            <div class="block-header block-header-default">
                <h3 class="block-title">@lang('company.index.table.company_list.title')</h3>
            </div>
            <div class="block-content">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-vcenter">
                        <thead>
                            <th>@lang('company.index.table.company_list.header.name')</th>
                            <th>@lang('company.index.table.company_list.header.address')</th>
                            <th>@lang('company.index.table.company_list.header.tax_id')</th>
                            <th>@lang('company.index.table.company_list.header.default')</th>
                            <th>@lang('company.index.table.company_list.header.frontweb')</th>
                            <th>@lang('company.index.table.company_list.header.status')</th>
                            <th>@lang('company.index.table.company_list.header.remarks')</th>
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
    <script type="application/javascript" src="{{ mix('js/apps/company.min.js') }}"></script>
@endsection
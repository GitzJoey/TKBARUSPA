@extends('layouts.codebase.master')

@section('title')
    @lang('search.result.title')
@endsection

@section('page_title')
    @lang('search.result.page_title')
@endsection

@section('page_title_desc')
    @lang('search.result.page_title_desc')
@endsection

@section('breadcrumbs')
    {!! Breadcrumbs::render('search') !!}
@endsection

@section('content')
    <div class="font-size-h3 font-w600 py-30 mb-20 text-center border-b">
        <span class="text-primary font-w700">{{ $resultCount }}</span>&nbsp;@lang('search.result.labels.item')&nbsp;<mark class="text-danger">{{ $query }}</mark>
    </div>
    <div class="block block-shadow-on-hover block-mode-loading-refresh" id="customerBlock">
        <div class="block-header block-header-default">
            <h3 class="block-title">@lang('search.result.panel.customer')</h3>
            <div class="block-options">
                <button type="button" class="btn-block-option">
                    <i class="si si-refresh"></i>
                </button>
                <button type="button" class="btn-block-option" data-toggle="block-option" data-action="content_toggle"></button>
            </div>
        </div>
        <div class="block-content">
        </div>
    </div>
    <div class="block block-shadow-on-hover block-mode-loading-refresh" id="supplierBlock">
        <div class="block-header block-header-default">
            <h3 class="block-title">@lang('search.result.panel.supplier')</h3>
            <div class="block-options">
                <button type="button" class="btn-block-option">
                    <i class="si si-refresh"></i>
                </button>
                <button type="button" class="btn-block-option" data-toggle="block-option" data-action="content_toggle"></button>
            </div>
        </div>
        <div class="block-content">
        </div>
    </div>
    <div class="block block-shadow-on-hover block-mode-loading-refresh" id="productBlock">
        <div class="block-header block-header-default">
            <h3 class="block-title">@lang('search.result.panel.product')</h3>
            <div class="block-options">
                <button type="button" class="btn-block-option">
                    <i class="si si-refresh"></i>
                </button>
                <button type="button" class="btn-block-option" data-toggle="block-option" data-action="content_toggle"></button>
            </div>
        </div>
        <div class="block-content">
        </div>
    </div>
    <div class="block block-shadow-on-hover block-mode-loading-refresh" id="poBlock">
        <div class="block-header block-header-default">
            <h3 class="block-title">@lang('search.result.panel.purchase_order')</h3>
            <div class="block-options">
                <button type="button" class="btn-block-option">
                    <i class="si si-refresh"></i>
                </button>
                <button type="button" class="btn-block-option" data-toggle="block-option" data-action="content_toggle"></button>
            </div>
        </div>
        <div class="block-content">
        </div>
    </div>
    <div class="block block-shadow-on-hover block-mode-loading-refresh" id="soBlock">
        <div class="block-header block-header-default">
            <h3 class="block-title">@lang('search.result.panel.sales_order')</h3>
            <div class="block-options">
                <button type="button" class="btn-block-option">
                    <i class="si si-refresh"></i>
                </button>
                <button type="button" class="btn-block-option" data-toggle="block-option" data-action="content_toggle"></button>
            </div>
        </div>
        <div class="block-content">
        </div>
    </div>
    <div class="block block-shadow-on-hover block-mode-loading-refresh" id="poPaymentBlock">
        <div class="block-header block-header-default">
            <h3 class="block-title">@lang('search.result.panel.purchase_order_payment')</h3>
            <div class="block-options">
                <button type="button" class="btn-block-option">
                    <i class="si si-refresh"></i>
                </button>
                <button type="button" class="btn-block-option" data-toggle="block-option" data-action="content_toggle"></button>
            </div>
        </div>
        <div class="block-content">
        </div>
    </div>
    <div class="block block-shadow-on-hover block-mode-loading-refresh" id="soPaymentBlock">
        <div class="block-header block-header-default">
            <h3 class="block-title">@lang('search.result.panel.sales_order_payment')</h3>
            <div class="block-options">
                <button type="button" class="btn-block-option">
                    <i class="si si-refresh"></i>
                </button>
                <button type="button" class="btn-block-option" data-toggle="block-option" data-action="content_toggle"></button>
            </div>
        </div>
        <div class="block-content">
        </div>
    </div>
    <div class="block block-shadow-on-hover block-mode-loading-refresh" id="stockBlock">
        <div class="block-header block-header-default">
            <h3 class="block-title">@lang('search.result.panel.stock')</h3>
            <div class="block-options">
                <button type="button" class="btn-block-option">
                    <i class="si si-refresh"></i>
                </button>
                <button type="button" class="btn-block-option" data-toggle="block-option" data-action="content_toggle"></button>
            </div>
        </div>
        <div class="block-content">
        </div>
    </div>
@endsection
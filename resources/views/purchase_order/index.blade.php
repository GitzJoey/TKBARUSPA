@extends('layouts.codebase.master')

@section('title')
    @lang('purchase_order.index.title')
@endsection

@section('page_title')
    <span class="fa fa-cart-plus fa-fw"></span>&nbsp;@lang('purchase_order.index.page_title')
@endsection

@section('page_title_desc')
    @lang('purchase_order.index.page_title_desc')
@endsection

@section('breadcrumbs')

@endsection

@section('content')
    <div id="poVue">
        @include ('layouts.common.error')
        <div class="block block-shadow-on-hover block-mode-loading-refresh" id="poListBlock">
            <div class="block-header block-header-default">
                <h3 class="block-title">@lang('purchase_order.index.panel.list_panel.title')</h3>
                <div class="block-options">
                    <button type="button" class="btn-block-option" data-toggle="block-option" data-action="fullscreen_toggle"></button>
                    <button type="button" class="btn-block-option" v-on:click="getAllPO">
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
                                <th class="text-center" width="15%">@lang('purchase_order.index.table.list_table.header.code')</th>
                                <th class="text-center" width="15%">@lang('purchase_order.index.table.list_table.header.po_date')</th>
                                <th class="text-center" width="25%">@lang('purchase_order.index.table.list_table.header.supplier')</th>
                                <th class="text-center" width="15%">@lang('purchase_order.index.table.list_table.header.shipping_date')</th>
                                <th class="text-center" width="20%">@lang('purchase_order.index.table.list_table.header.status')</th>
                                <th class="text-center" width="10%">@lang('labels.ACTION')</th>
                            </tr>
                        </thead>
                        <tbody>
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
        <div class="block block-shadow-on-hover block-mode-loading-refresh" id="poCRUDBlock">
            <div class="block-header block-header-default">
                <h3 class="block-title">
                    <template v-if="mode == 'create'">@lang('purchase_order.index.panel.crud_panel.title_create')</template>
                    <template v-if="mode == 'show'">@lang('purchase_order.index.panel.crud_panel.title_show')</template>
                    <template v-if="mode == 'edit'">@lang('purchase_order.index.panel.crud_panel.title_edit')</template>
                </h3>
                <div class="block-options">
                    <button type="button" class="btn-block-option" data-toggle="block-option" data-action="fullscreen_toggle"></button>
                    <button type="button" class="btn-block-option" data-toggle="block-option" data-action="content_toggle"></button>
                </div>
            </div>
            <div class="block-content">
                <form id="poForm" method="post" v-on:submit.prevent="validateBeforeSubmit">
                </form>
            </div>
        </div>
    </div>
@endsection

@section('custom_js')
    <script type="application/javascript">
        var poVue = new Vue ({
            el: '#poVue',
            data: {
                poList: [],
                statusDDL: [],
                mode: '',
                po: { }
            },
            mounted: function () {
                this.mode = 'list';
                this.getAllPO();
                this.getLookupStatus();
            },
            methods: {
                validateBeforeSubmit: function() {
                    this.$validator.validateAll().then(isValid => {
                        if (!isValid) return;
                        Codebase.blocks('#poCRUDBlock', 'state_toggle');
                        if (this.mode == 'create') {
                            axios.post('/api/post/po/save', new FormData($('#poForm')[0])).then(response => {
                                this.backToList();
                            }).catch(e => { this.handleErrors(e); });
                        } else if (this.mode == 'edit') {
                            axios.post('/api/post/po/edit/' + this.po.hId, new FormData($('#poForm')[0])).then(response => {
                                this.backToList();
                            }).catch(e => { this.handleErrors(e); });
                        } else { }
                        Codebase.blocks('#poCRUDBlock', 'state_toggle');
                    });
                },
                getAllPO: function() {
                    Codebase.blocks('#poListBlock', 'state_toggle');
                    axios.get('/api/get/po/read').then(response => {
                        this.poList = response.data;
                        Codebase.blocks('#poListBlock', 'state_toggle');
                    }).catch(e => { this.handleErrors(e); });
                },
                createNew: function() {
                    this.mode = 'create';
                    this.errors.clear();
                    this.po = this.emptyPO();
                },
                editSelected: function(idx) {
                    this.mode = 'edit';
                    this.errors.clear();
                    this.po = this.poList[idx];
                },
                showSelected: function(idx) {
                    this.mode = 'show';
                    this.errors.clear();
                    this.po = this.poList[idx];
                },
                deleteSelected: function(idx) {
                    axios.post('/api/post/po/delete/' + idx).then(response => {
                        this.backToList();
                    }).catch(e => { this.handleErrors(e); });
                },
                backToList: function() {
                    this.mode = 'list';
                    this.errors.clear();
                    this.getAllPO();
                },
                emptyPO: function() {
                    return {
                        hId: '',
                        po_created: '',
                        shipping_date: '',
                        supplier_type: {
                            code: ''
                        },
                        supplier: {
                            hId: '',
                            show: false
                        },
                        warehouse: {
                            hId: ''
                        },
                        vendorTrucking: {
                            hId: ''
                        },
                        poType: {
                            code: ''
                        },
                        product: {
                            hId: ''
                        },
                        items: [],
                        expenses: [],
                        disc_total_percent : 0,
                        disc_total_value : 0
                    }
                },
                getLookupStatus: function() {
                    axios.get('/api/get/lookup/byCategory/PO_STATUS').then(
                        response => { this.statusDDL = response.data; }
                    );
                }
            },
            watch: {
                mode: function() {
                    switch (this.mode) {
                        case 'create':
                        case 'edit':
                        case 'show':
                            Codebase.blocks('#poListBlock', 'close')
                            Codebase.blocks('#poCRUDBlock', 'open')
                            break;
                        case 'list':
                        default:
                            Codebase.blocks('#poListBlock', 'open')
                            Codebase.blocks('#poCRUDBlock', 'close')
                            break;
                    }
                }
            },
            computed: {
                defaultStatus: function() {
                    return '';
                }
            }
        });
    </script>
    <script type="application/javascript" src="{{ mix('js/apps/po.js') }}"></script>
@endsection
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
        <div class="block block-shadow-on-hover block-mode-loading-refresh" id="companyListBlock">
            <div class="block-header block-header-default">
                <h3 class="block-title">@lang('company.index.table.company_list.title')</h3>
                <div class="block-options">
                    <button type="button" class="btn-block-option" data-toggle="block-option" data-action="fullscreen_toggle"></button>
                    <button type="button" class="btn-block-option" v-on:click="getAllCompany">
                        <i class="si si-refresh"></i>
                    </button>
                    <button type="button" class="btn-block-option" data-toggle="block-option" data-action="content_toggle"></button>
                </div>
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
                            <th class="text-center action-column-width">@lang('labels.ACTION')</th>
                        </thead>
                        <tbody>
                            <tr v-for="(c, cIdx) in companyList">
                                <td>@{{ c.name }}</td>
                                <td>@{{ c.address }}</td>
                                <td>@{{ c.tax_id }}</td>
                                <td>@{{ c.defaultI18n }}</td>
                                <td>@{{ c.frontwebI18n }}</td>
                                <td>@{{ c.statusI18n }}</td>
                                <td>@{{ c.remarks }}</td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <button class="btn btn-sm btn-secondary" v-on:click="showSelected(cIdx)"><span class="fa fa-info fa-fw"></span></button>
                                        <button class="btn btn-sm btn-secondary" v-on:click="editSelected(cIdx)"><span class="fa fa-pencil fa-fw"></span></button>
                                        <button class="btn btn-sm btn-secondary" v-on:click="deleteSelected(c.hId)"><span class="fa fa-close fa-fw"></span></button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom_js')
    <script type="application/javascript">
        var unitVue = new Vue ({
            el: '#companyVue',
            data: {
                companyList: [],
                statusDDL: [],
                yesnoDDL: [],
                mode: '',
                company: { }
            },
            mounted: function () {
                this.mode = 'list';
                this.getAllCompany();
                this.getLookupStatus();
            },
            methods: {
                validateBeforeSubmit: function() {
                    this.$validator.validateAll().then(isValid => {
                        if (!isValid) return;
                        if (this.mode == 'create') {
                            axios.post('/api/post/company/save', new FormData($('#unitForm')[0])).then(response => {
                                this.backToList();
                            }).catch(e => { this.handleErrors(e); });
                        } else if (this.mode == 'edit') {
                            axios.post('/api/post/company/edit/' + this.company.hId, new FormData($('#companyForm')[0])).then(response => {
                                this.backToList();
                            }).catch(e => { this.handleErrors(e); });
                        } else { }
                    });
                },
                getAllCompany: function() {
                    Codebase.blocks('#companyListBlock', 'state_toggle');
                    axios.get('/api/get/company/readAll').then(response => {
                        this.companyList = response.data;
                        Codebase.blocks('#companyListBlock', 'state_toggle');
                    }).catch(e => { this.handleErrors(e); });
                },
                createNew: function() {
                    this.mode = 'create';
                    this.unit = this.emptyCompany();
                },
                editSelected: function(idx) {
                    this.mode = 'edit';
                    this.unit = this.unitList[idx];
                },
                showSelected: function(idx) {
                    this.mode = 'show';
                    this.company = this.companyList[idx];
                },
                deleteSelected: function(idx) {
                    axios.post('/api/post/company/delete/' + idx).then(response => {
                        this.backToList();
                    }).catch(e => { this.handleErrors(e); });
                },
                backToList: function() {
                    this.mode = 'list';
                    this.errors.clear();
                    this.getAllCompany();
                },
                emptyCompany: function() {
                    return {
                        hId: '',
                        name: '',
                        status: '',
                        remarks: ''
                    }
                },
                getLookupStatus: function() {
                    axios.get('/api/get/lookup/byCategory/STATUS').then(
                        response => { this.statusDDL = response.data; }
                    );
                },
                getLookupYesNo: function() {
                    axios.get('/api/get/lookup/byCategory/YESNOSELECT').then(
                        response => { this.yesnoDDL = response.data; }
                    );
                }
            },
            watch: {
                mode: function() {
                    switch (this.mode) {
                        case 'create':
                        case 'edit':
                        case 'show':
                            Codebase.blocks('#companyListBlock', 'close')
                            Codebase.blocks('#companyCRUDBlock', 'open')
                            break;
                        case 'list':
                        default:
                            Codebase.blocks('#companyListBlock', 'open')
                            Codebase.blocks('#companyCRUDBlock', 'close')
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
    <script type="application/javascript" src="{{ mix('js/apps/company.min.js') }}"></script>
@endsection
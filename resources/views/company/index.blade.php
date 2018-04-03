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
        <div class="block block-shadow-on-hover" id="companyCRUDBlock">
            <div class="block-header block-header-default">
                <h3 class="block-title">@lang('company.index.field.title')</h3>
                <div class="block-options">
                    <button type="button" class="btn-block-option" data-toggle="block-option" data-action="fullscreen_toggle"></button>
                    <button type="button" class="btn-block-option" data-toggle="block-option" data-action="content_toggle"></button>
                </div>
            </div>
            <div class="block-content">
                <ul class="nav nav-tabs nav-tabs-alt" data-toggle="tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" href="#tabs_company">
                            @lang('company.index.tabs.company')
                            &nbsp;<span id="companyDataTabError" v-bind:class="{'is-invalid':true, 'hidden':errors.any('tabs_company')?false:true}"><i class="fa fa-close fa-fw"></i></span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#tabs_bankaccount">
                            @lang('company.index.tabs.bank_account')
                            &nbsp;<span id="bankAccountTabError" v-bind:class="{'is-invalid':true, 'hidden':errors.any('tabs_bankaccount')?false:true}"><i class="fa fa-close fa-fw"></i></span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#tabs_currencies">
                            @lang('company.index.tabs.currencies')
                            &nbsp;<span id="currenciesTabError" v-bind:class="{'is-invalid':true, 'hidden':errors.any('tabs_currencies')?false:true}"><i class="fa fa-close fa-fw"></i></span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#tabs_settings">
                            @lang('company.index.tabs.settings')
                            &nbsp;<span id="settingsTabError" v-bind:class="{'is-invalid':true, 'hidden':errors.any('tabs_settings')?false:true}"><i class="fa fa-close fa-fw"></i></span>
                        </a>
                    </li>
                </ul>
                <div class="block-content tab-content overflow-hidden">
                    <div class="tab-pane fade fade-up show active" id="tabs_company" role="tabpanel">
                        <div v-bind:class="{ 'form-group':true, 'row':true, 'is-invalid':errors.has('tabs_company.name') }">
                            <label for="inputCompanyName" class="col-12">@lang('company.fields.name')</label>
                            <div class="col-md-10">
                                <input id="inputCompanyName" name="name" type="text" class="form-control" placeholder="{{ trans('company.fields.name') }}"
                                       v-validate="'required'" data-vv-as="{{ trans('company.fields.name') }}" data-vv-scope="tabs_store">
                                <span v-show="errors.has('tabs_store.name')" class="invalid-feedback">@{{ errors.first('tabs_store.name') }}</span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="inputImage" class="col-12">&nbsp;</label>
                            <div class="col-md-10">
                                <input id="inputImage" name="image_path" type="file" class="form-control" data-show-upload="false" data-allowed-file-extensions='["jpg","png"]'>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="inputAddress" class="col-12">@lang('company.fields.address')</label>
                            <div class="col-md-9">
                                <textarea id="inputAddress" class="form-control" rows="5" name="address"></textarea>
                            </div>
                            <div class="col-md-1">
                                <button id="btnChooseLocation" type="button" class="btn btn-default" data-toggle="modal" data-target="#myModal"><i class="fa fa-location-arrow"></i></button>
                                <input id="inputLatitude" type="hidden" name="latitude">
                                <input id="inputLongitude" type="hidden" name="longitude">
                            </div>
                        </div>
                        <div v-bind:class="{ 'form-group':true, 'row':true, 'is-invalid':errors.has('tabs_company.phone') }">
                            <label for="inputPhone" class="col-12">@lang('company.fields.phone')</label>
                            <div class="col-md-10">
                                <input id="inputPhone" name="phone_num" type="text" class="form-control" placeholder="{{ trans('company.fields.phone') }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="inputFax" class="col-12 control-label">@lang('company.fields.fax')</label>
                            <div class="col-sm-10">
                                <input id="inputFax" name="fax_num" type="text" class="form-control" placeholder="{{ trans('company.fields.fax') }}">
                            </div>
                        </div>
                        <div v-bind:class="{ 'form-group':true, 'row':true, 'is-invalid':errors.has('tabs_company.tax_id') }">
                            <label for="inputTax" class="col-12">@lang('company.fields.tax_id')</label>
                            <div class="col-sm-10">
                                <input id="inputTax" name="tax_id" type="text" class="form-control" placeholder="{{ trans('company.fields.tax_id') }}"
                                       v-validate="'required'" data-vv-as="{{ trans('company.fields.tax_id') }}" data-vv-scope="tabs_store">
                                <span v-show="errors.has('tabs_store.tax_id')" class="invalid-feedback">@{{ errors.first('tabs_store.tax_id') }}</span>
                            </div>
                        </div>
                        <div v-bind:class="{ 'form-group':true, 'row':true, 'is-invalid':errors.has('tabs_store.status') }">
                            <label for="inputStatus" class="col-12">@lang('company.fields.status')</label>
                            <div class="col-md-10">
                                <select class="form-control"
                                        name="status"
                                        v-model="status"
                                        v-validate="'required|checkactive'"
                                        data-vv-as="{{ trans('company.fields.status') }}"
                                        data-vv-scope="tabs_store">
                                    <option v-bind:value="defaultStatus">@lang('labels.PLEASE_SELECT')</option>
                                    <option v-for="(s, sIdx) in statusDDL" v-bind:value="s.code">@{{ s.description }}</option>
                                </select>
                                <span v-show="errors.has('tabs_store.status')" class="invalid-feedback">@{{ errors.first('tabs_store.status') }}</span>
                            </div>
                        </div>
                        <div v-bind:class="{ 'form-group':true, 'row':true, 'is-invalid':errors.has('tabs_company.is_default') }">
                            <label for="inputIsDefault" class="col-12">@lang('company.fields.default')</label>
                            <div class="col-sm-10">
                                <select class="form-control"
                                        name="is_default"
                                        v-model="is_default"
                                        v-validate="'required'"
                                        data-vv-as="{{ trans('company.fields.default') }}"
                                        data-vv-scope="tabs_store">
                                    <option v-bind:value="defaultYesNo">@lang('labels.PLEASE_SELECT')</option>
                                    <option v-for="(yn, ynIdx) in yesnoDDL" v-bind:value="yn.code">@{{ yn.description }}</option>
                                </select>
                                <span v-show="errors.has('tabs_store.is_default')" class="invalid-feedback">@{{ errors.first('tabs_store.is_default') }}</span>
                            </div>
                        </div>
                        <div v-bind:class="{ 'form-group':true, 'row':true, 'is-invalid':errors.has('tabs_store.frontweb') }">
                            <label for="inputFrontWeb" class="col-12">@lang('company.fields.frontweb')</label>
                            <div class="col-md-10">
                                <select class="form-control"
                                        name="frontweb"
                                        v-validate="'required'"
                                        data-vv-as="{{ trans('company.fields.frontweb') }}"
                                        data-vv-scope="tabs_store">
                                    <option v-bind:value="defaultYesNo">@lang('labels.PLEASE_SELECT')</option>
                                    <option v-for="(yn, ynIdx) in yesnoDDL" v-bind:value="yn.code">@{{ yn.description }}</option>
                                </select>
                                <span v-show="errors.has('tabs_store.frontweb')" class="invalid-feedback">@{{ errors.first('tabs_store.frontweb') }}</span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="inputRemarks" class="col-12">@lang('company.fields.remarks')</label>
                            <div class="col-sm-10">
                                <input id="inputRemarks" name="remarks" type="text" class="form-control" placeholder="{{ trans('company.fields.remarks') }}">
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade fade-up" id="tabs_bankaccount" role="tabpanel">
                        <h4 class="font-w400">Profile Content</h4>
                        <p>Content slides up..</p>
                    </div>
                    <div class="tab-pane fade fade-up" id="tabs_currencies" role="tabpanel">
                        <h4 class="font-w400">Settings Content</h4>
                        <p>Content slides up..</p>
                    </div>
                    <div class="tab-pane fade fade-up" id="tabs_settings" role="tabpanel">
                        <h4 class="font-w400">Settings Content</h4>
                        <p>Content slides up..</p>
                    </div>
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
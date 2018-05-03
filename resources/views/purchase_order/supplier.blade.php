<div class="modal" id="supplierDetailModal" tabindex="-1" role="dialog" aria-labelledby="supplierDetailModal" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="block block-themed block-transparent mb-0">
                <div class="block-header bg-primary-dark">
                    <h3 class="block-title">@{{ po.supplier.name }}</h3>
                </div>
                <div class="block-content">
                    <ul class="nav nav-tabs nav-tabs-alt" data-toggle="tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" href="#tabs_supplier">
                                @lang('supplier.index.tabs.supplier')
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#tabs_pic">
                                @lang('supplier.index.tabs.pic')
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#tabs_bankaccounts">
                                @lang('supplier.index.tabs.bank_accounts')
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#tabs_product">
                                @lang('supplier.index.tabs.product')
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#tabs_settings">
                                @lang('supplier.index.tabs.settings')
                            </a>
                        </li>
                    </ul>
                    <div class="block-content tab-content overflow-hidden">
                        <div class="tab-pane fade fade-up show active" id="tabs_supplier" role="tabpanel">
                            <div v-bind:class="{ 'form-group row':true, 'is-invalid':errors.has('tabs_supplier.name') }">
                                <label for="inputName" class="col-2 col-form-label">@lang('supplier.fields.name')</label>
                                <div class="col-md-10">
                                    <div class="form-control-plaintext">@{{ po.supplier.name }}</div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="inputCodeSign" class="col-2 col-form-label">@lang('supplier.fields.code_sign')</label>
                                <div class="col-md-10">
                                    <div class="form-control-plaintext">@{{ po.supplier.code_sign }}</div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="inputAddress" class="col-2 col-form-label">@lang('supplier.fields.address')</label>
                                <div class="col-md-10">
                                    <div class="form-control-plaintext">@{{ po.supplier.address }}</div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="inputCity" class="col-2 col-form-label">@lang('supplier.fields.city')</label>
                                <div class="col-md-10">
                                    <div class="form-control-plaintext">@{{ po.supplier.city }}</div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="inputPhone" class="col-2 col-form-label">@lang('supplier.fields.phone')</label>
                                <div class="col-md-10">
                                    <div class="form-control-plaintext">@{{ po.supplier.phone_number }}</div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="inputFax" class="col-2 col-form-label">@lang('supplier.fields.fax_num')</label>
                                <div class="col-md-10">
                                    <div class="form-control-plaintext">@{{ po.supplier.fax_num }}</div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="inputTaxId" class="col-2 col-form-label">@lang('supplier.fields.tax_id')</label>
                                <div class="col-md-10">
                                    <div class="form-control-plaintext">@{{ po.supplier.tax_id }}</div>
                                </div>
                            </div>
                            <div v-bind:class="{ 'form-group row':true, 'is-invalid':errors.has('tabs_supplier.status') }">
                                <label for="inputStatus" class="col-2 col-form-label">@lang('supplier.fields.status')</label>
                                <div class="col-md-10">
                                    <div class="form-control-plaintext">@{{ po.supplier.statusI18n }}</div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="inputRemarks" class="col-2 col-form-label">@lang('supplier.fields.remarks')</label>
                                <div class="col-md-10">
                                    <div class="form-control-plaintext">@{{ po.supplier.remarks }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade fade-up show" id="tabs_pic" role="tabpanel">
                            <div class="row">
                                <div class="col-12">
                                    <div v-for="(p, pIdx) in po.supplier.persons_in_charge">
                                        <div class="block block-shadow-on-hover block-mode-loading-refresh">
                                            <div class="block-header block-header-default">
                                                <h3 class="block-title">@lang('supplier.index.panel.pic.title')&nbsp;@{{ pIdx + 1 }}</h3>
                                            </div>
                                            <div class="block-content">
                                                <div v-bind:class="{ 'form-group row':true, 'is-invalid':errors.has('tabs_pic.first_name_' + pIdx) }">
                                                    <label for="inputFirstName" class="col-2 col-form-label">@lang('supplier.fields.first_name')</label>
                                                    <div class="col-md-10">
                                                        <div class="form-control-plaintext">@{{ p.first_name }}</div>
                                                    </div>
                                                </div>
                                                <div v-bind:class="{ 'form-group row':true, 'is-invalid':errors.has('tabs_pic.last_name_' + pIdx) }">
                                                    <label for="inputLastName" class="col-2 col-form-label">@lang('supplier.fields.last_name')</label>
                                                    <div class="col-md-10">
                                                        <div class="form-control-plaintext">@{{ p.last_name }}</div>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="inputAddress" class="col-2 col-form-label">@lang('supplier.fields.address')</label>
                                                    <div class="col-md-10">
                                                        <div class="form-control-plaintext">@{{ p.address }}</div>
                                                    </div>
                                                </div>
                                                <div v-bind:class="{ 'form-group row':true, 'is-invalid':errors.has('tabs_pic.ic_num_' + pIdx) }">
                                                    <label for="inputICNum" class="col-2 col-form-label">@lang('supplier.fields.ic_num')</label>
                                                    <div class="col-md-10">
                                                        <div class="form-control-plaintext">@{{ p.ic_num }}</div>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="inputPhoneNumber" class="col-2 col-form-label">@lang('supplier.fields.phone_number')</label>
                                                    <div class="col-md-10">
                                                        <table class="table table-bordered">
                                                            <thead class="thead-light">
                                                                <tr>
                                                                    <th>@lang('supplier.index.table.table_phone.header.provider')</th>
                                                                    <th>@lang('supplier.index.table.table_phone.header.number')</th>
                                                                    <th>@lang('supplier.index.table.table_phone.header.remarks')</th>
                                                                    <th class="text-center">@lang('labels.ACTION')</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr v-for="(ph, phIdx) in p.phone_numbers">
                                                                    <td v-bind:class="{ 'is-invalid':errors.has('tabs_pic.profile_' + pIdx + '_phoneprovider_' + phIdx) }">
                                                                        <input type="hidden" v-bind:name="'profile_' + pIdx + '_phone_numbers_id[]'" v-model="ph.hId"/>
                                                                        <div class="form-control-plaintext">@{{ ph.provider.fullName }}</div>
                                                                    </td>
                                                                    <td v-bind:class="{ 'is-invalid':errors.has('tabs_pic.profile_' + pIdx + '_number_' + phIdx) }">
                                                                        <div class="form-control-plaintext">@{{ ph.number }}</div>
                                                                    </td>
                                                                    <td>
                                                                        <div class="form-control-plaintext">@{{ ph.remarks }}</div>
                                                                    </td>
                                                                    <td class="text-center">
                                                                        <div class="form-control-plaintext">&nbsp;</div>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade fade-up show" id="tabs_bankaccounts" role="tabpanel">
                            <table class="table table-bordered">
                                <thead class="thead-light">
                                    <tr>
                                        <th class="text-center">@lang('supplier.index.table.table_bank.header.bank')</th>
                                        <th class="text-center">@lang('supplier.index.table.table_bank.header.account_name')</th>
                                        <th class="text-center">@lang('supplier.index.table.table_bank.header.account_number')</th>
                                        <th class="text-center">@lang('supplier.index.table.table_bank.header.remarks')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(ba, baIdx) in po.supplier.bank_accounts">
                                        <td v-bind:class="{ 'is-invalid':errors.has('tabs_bankaccounts.bank_' + baIdx) }">
                                            <input type="hidden" name="bank_account_id[]" v-model="ba.hId"/>
                                            <div class="form-control-plaintext">@{{ ba.bank.bankFullName }}</div>
                                        </td>
                                        <td v-bind:class="{ 'is-invalid':errors.has('tabs_bankaccounts.account_name_' + baIdx) }">
                                            <div class="form-control-plaintext">@{{ ba.account_name }}</div>
                                        </td>
                                        <td v-bind:class="{ 'is-invalid':errors.has('tabs_bankaccounts.account_number_' + baIdx) }">
                                            <div class="form-control-plaintext">@{{ ba.account_number }}</div>
                                        </td>
                                        <td>
                                            <div class="form-control-plaintext">@{{ ba.remarks }}</div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane fade fade-up show" id="tabs_product" role="tabpanel">
                            <table class="table table-bordered">
                                <thead class="thead-light">
                                    <tr>
                                        <th class="text-center">@lang('supplier.index.table.table_prod.header.type')</th>
                                        <th class="text-center">@lang('supplier.index.table.table_prod.header.name')</th>
                                        <th class="text-center">@lang('supplier.index.table.table_prod.header.short_code')</th>
                                        <th class="text-center">@lang('supplier.index.table.table_prod.header.description')</th>
                                        <th class="text-center">@lang('supplier.index.table.table_prod.header.remarks')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(pL, pLIdx) in po.supplier.products">
                                        <td>@{{ pL.product_type.name }}</td>
                                        <td>@{{ pL.name }}</td>
                                        <td>@{{ pL.short_code }}</td>
                                        <td>@{{ pL.description }}</td>
                                        <td>@{{ pL.remarks }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane fade fade-up show" id="tabs_settings" role="tabpanel">
                            <div v-bind:class="{ 'form-group row':true, 'is-invalid':errors.has('tabs_settings.payment_due_day') }">
                                <label for="inputPaymentDueDay" class="col-2 col-form-label">@lang('supplier.fields.payment_due_day')</label>
                                <div class="col-md-10">
                                    <div class="form-control-plaintext">@{{ po.supplier.payment_due_day }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-alt-secondary" data-dismiss="modal">@lang('buttons.close_button')</button>
            </div>
        </div>
    </div>
</div>
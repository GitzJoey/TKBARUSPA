<div class="modal" id="customerDetailModal" tabindex="-1" role="dialog" aria-labelledby="customerDetailModal" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-popin" role="document">
        <div class="modal-content">
            <div class="block block-themed block-transparent mb-0">
                <div class="block-header bg-primary-dark">
                    <h3 class="block-title">@{{ selectedCustomer.name }}</h3>
                </div>
                <div class="block-content">
                    <ul class="nav nav-tabs nav-tabs-alt" data-toggle="tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" href="#tabs_customer">
                                @lang('customer.index.tabs.customer')
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#tabs_pic">
                                @lang('customer.index.tabs.pic')
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#tabs_bankaccounts">
                                @lang('customer.index.tabs.bank_accounts')
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#tabs_settings">
                                @lang('customer.index.tabs.settings')
                            </a>
                        </li>
                    </ul>
                    <div class="block-content tab-content overflow-hidden">
                        <div class="tab-pane fade fade-up show active" id="tabs_customer" role="tabpanel">
                            <div v-bind:class="{ 'form-group row':true, 'is-invalid':errors.has('tabs_customer.name') }">
                                <label for="inputName" class="col-2 col-form-label">@lang('customer.fields.name')</label>
                                <div class="col-md-10">
                                    <div class="form-control-plaintext">@{{ selectedCustomer.name }}</div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="inputCodeSign" class="col-2 col-form-label">@lang('customer.fields.code_sign')</label>
                                <div class="col-md-10">
                                    <div class="form-control-plaintext">@{{ selectedCustomer.code_sign }}</div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="inputAddress" class="col-2 col-form-label">@lang('customer.fields.address')</label>
                                <div class="col-md-10">
                                    <div class="form-control-plaintext">@{{ selectedCustomer.address }}</div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="inputCity" class="col-2 col-form-label">@lang('customer.fields.city')</label>
                                <div class="col-md-10">
                                    <div class="form-control-plaintext">@{{ selectedCustomer.city }}</div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="inputPhone" class="col-2 col-form-label">@lang('customer.fields.phone')</label>
                                <div class="col-md-10">
                                    <div class="form-control-plaintext">@{{ selectedCustomer.phone_number }}</div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="inputFax" class="col-2 col-form-label">@lang('customer.fields.fax_num')</label>
                                <div class="col-md-10">
                                    <div class="form-control-plaintext">@{{ selectedCustomer.fax_num }}</div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="inputTaxId" class="col-2 col-form-label">@lang('customer.fields.tax_id')</label>
                                <div class="col-md-10">
                                    <div class="form-control-plaintext">@{{ selectedCustomer.tax_id }}</div>
                                </div>
                            </div>
                            <div v-bind:class="{ 'form-group row':true, 'is-invalid':errors.has('tabs_customer.status') }">
                                <label for="inputStatus" class="col-2 col-form-label">@lang('customer.fields.status')</label>
                                <div class="col-md-10">
                                    <div class="form-control-plaintext">@{{ selectedCustomer.statusI18n }}</div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="inputRemarks" class="col-2 col-form-label">@lang('customer.fields.remarks')</label>
                                <div class="col-md-10">
                                    <div class="form-control-plaintext">@{{ selectedCustomer.remarks }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade fade-up show" id="tabs_pic" role="tabpanel">
                            <div class="row">
                                <div class="col-2">
                                    &nbsp;
                                </div>
                                <div class="col-10">
                                    <div v-for="(p, pIdx) in selectedCustomer.persons_in_charge">
                                        <div class="block block-shadow-on-hover block-mode-loading-refresh">
                                            <div class="block-header block-header-default">
                                                <h3 class="block-title">@lang('customer.index.panel.pic.title')&nbsp;@{{ pIdx + 1 }}</h3>
                                            </div>
                                            <div class="block-content">
                                                <div v-bind:class="{ 'form-group row':true, 'is-invalid':errors.has('tabs_pic.first_name_' + pIdx) }">
                                                    <label for="inputFirstName" class="col-2 col-form-label">@lang('customer.fields.first_name')</label>
                                                    <div class="col-md-10">
                                                        <div class="form-control-plaintext">@{{ p.first_name }}</div>
                                                    </div>
                                                </div>
                                                <div v-bind:class="{ 'form-group row':true, 'is-invalid':errors.has('tabs_pic.last_name_' + pIdx) }">
                                                    <label for="inputLastName" class="col-2 col-form-label">@lang('customer.fields.last_name')</label>
                                                    <div class="col-md-10">
                                                        <div class="form-control-plaintext">@{{ p.last_name }}</div>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="inputAddress" class="col-2 col-form-label">@lang('customer.fields.address')</label>
                                                    <div class="col-md-10">
                                                        <div class="form-control-plaintext">@{{ p.address }}</div>
                                                    </div>
                                                </div>
                                                <div v-bind:class="{ 'form-group row':true, 'is-invalid':errors.has('tabs_pic.ic_num_' + pIdx) }">
                                                    <label for="inputICNum" class="col-2 col-form-label">@lang('customer.fields.ic_num')</label>
                                                    <div class="col-md-10">
                                                        <div class="form-control-plaintext">@{{ p.ic_num }}</div>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="inputPhoneNumber" class="col-2 col-form-label">@lang('customer.fields.phone_number')</label>
                                                    <div class="col-md-10">
                                                        <table class="table table-bordered">
                                                            <thead class="thead-light">
                                                                <tr>
                                                                    <th>@lang('customer.index.table.table_phone.header.provider')</th>
                                                                    <th>@lang('customer.index.table.table_phone.header.number')</th>
                                                                    <th>@lang('customer.index.table.table_phone.header.remarks')</th>
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
                                        <th class="text-center">@lang('customer.index.table.table_bank.header.bank')</th>
                                        <th class="text-center">@lang('customer.index.table.table_bank.header.account_name')</th>
                                        <th class="text-center">@lang('customer.index.table.table_bank.header.account_number')</th>
                                        <th class="text-center">@lang('customer.index.table.table_bank.header.remarks')</th>
                                        <th class="text-center">@lang('labels.ACTION')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(ba, baIdx) in selectedCustomer.bank_accounts">
                                        <td v-bind:class="{ 'is-invalid':errors.has('tabs_bankaccounts.bank_' + baIdx) }">
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
                                        <td class="text-center">
                                            <div class="form-control-plaintext">&nbsp;</div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane fade fade-up show" id="tabs_settings" role="tabpanel">
                            <div v-bind:class="{ 'form-group row':true, 'is-invalid':errors.has('tabs_settings.price_level_id') }">
                                <label for="inputPriceLevel" class="col-2 col-form-label">@lang('customer.fields.price_level')</label>
                                <div class="col-md-10">
                                    <div class="form-control-plaintext">@{{ selectedCustomer.price_level ? selectedCustomer.price_level.name:'' }}</div>
                                </div>
                            </div>
                            <div v-bind:class="{ 'form-group row':true, 'is-invalid':errors.has('tabs_settings.payment_due_day') }">
                                <label for="inputPaymentDueDay" class="col-2 col-form-label">@lang('customer.fields.payment_due_day')</label>
                                <div class="col-md-10">
                                    <div class="form-control-plaintext">@{{ selectedCustomer.payment_due_day }}</div>
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
@extends('layouts.codebase.master')

@section('title')
    @lang('user.index.title')
@endsection

@section('page_title')
    <span class="fa fa-user fa-fw"></span>&nbsp;@lang('user.index.page_title')
@endsection

@section('page_title_desc')
    @lang('user.index.page_title_desc')
@endsection

@section('breadcrumbs')

@endsection

@section('content')
    <div id="userVue">
        @include ('layouts.common.error')
        <div class="block block-shadow-on-hover block-mode-loading-refresh" id="userListBlock">
            <div class="block-header block-header-default">
                <h3 class="block-title">@lang('user.index.panel.list_panel.title')</h3>
                <div class="block-options">
                    <button type="button" class="btn-block-option" data-toggle="block-option" data-action="fullscreen_toggle"></button>
                    <button type="button" class="btn-block-option" v-on:click="getAllUser">
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
                                <th>@lang('user.index.table.user_list.header.name')</th>
                                <th>@lang('user.index.table.user_list.header.email')</th>
                                <th>@lang('user.index.table.user_list.header.roles')</th>
                                <th>@lang('user.index.table.user_list.header.company')</th>
                                <th>@lang('user.index.table.user_list.header.active')</th>
                                <th class="text-center">@lang('labels.ACTION')</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(u, uIdx) in userList" v-bind:class="{ 'table-danger': u.active ? false:true}">
                                <td>@{{ u.name }}</td>
                                <td>@{{ u.email }}</td>
                                <td>@{{ u.roles[0].name }}</td>
                                <td>@{{ u.company ? u.company.name:'' }}</td>
                                <td>@{{ u.activeI18n }}</td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <button class="btn btn-sm btn-secondary" v-on:click="showSelected(uIdx)"><span class="fa fa-info fa-fw"></span></button>
                                        <button class="btn btn-sm btn-secondary" v-on:click="editSelected(uIdx)"><span class="fa fa-pencil fa-fw"></span></button>
                                        <button class="btn btn-sm btn-secondary" v-on:click="deleteSelected(u.hId)"><span class="fa fa-close fa-fw"></span></button>
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
        <div class="block block-shadow-on-hover block-mode-loading-refresh" id="userCRUDBlock">
            <div class="block-header block-header-default">
                <h3 class="block-title">
                    <template v-if="mode == 'create'">@lang('user.index.panel.crud_panel.title_create')</template>
                    <template v-if="mode == 'show'">@lang('user.index.panel.crud_panel.title_show')</template>
                    <template v-if="mode == 'edit'">@lang('user.index.panel.crud_panel.title_edit')</template>
                </h3>
                <div class="block-options">
                    <button type="button" class="btn-block-option" data-toggle="block-option" data-action="fullscreen_toggle"></button>
                    <button type="button" class="btn-block-option" data-toggle="block-option" data-action="content_toggle"></button>
                </div>
            </div>
            <div class="block-content">
                <form id="userForm" method="post" v-on:submit.prevent="validateBeforeSubmit">
                    <div v-bind:class="{ 'form-group row':true, 'is-invalid':errors.has('first_name') }">
                        <label for="inputFirstName" class="col-2 col-form-label">@lang('user.fields.first_name')</label>
                        <div class="col-md-10">
                            <template v-if="mode == 'create' || mode == 'edit'">
                                <input id="inputFirstName" name="first_name" type="text" class="form-control" placeholder="@lang('user.fields.first_name')"
                                       v-model="user.profile[0].first_name" v-validate="'required'" data-vv-as="{{ trans('user.fields.first_name') }}">
                                <span v-show="errors.has('first_name')" class="invalid-feedback">@{{ errors.first('first_name') }}</span>
                            </template>
                            <template v-if="mode == 'show'">
                                <div class="form-control-plaintext">@{{ user.profile[0].first_name }}</div>
                            </template>
                        </div>
                    </div>
                    <div v-bind:class="{ 'form-group row':true, 'is-invalid':errors.has('last_name') }">
                        <label for="inputLastName" class="col-2 col-form-label">@lang('user.fields.last_name')</label>
                        <div class="col-md-10">
                            <template v-if="mode == 'create' || mode == 'edit'">
                                <input id="inputLastName" name="last_name" type="text" class="form-control" placeholder="@lang('user.fields.last_name')"
                                       v-model="user.profile[0].last_name" v-validate="'required'" data-vv-as="{{ trans('user.fields.last_name') }}">
                                <span v-show="errors.has('last_name')" class="invalid-feedback">@{{ errors.first('last_name') }}</span>
                            </template>
                            <template v-if="mode == 'show'">
                                <div class="form-control-plaintext">@{{ user.profile[0].last_name }}</div>
                            </template>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputAddress" class="col-2 col-form-label">@lang('user.fields.address')</label>
                        <div class="col-md-10">
                            <template v-if="mode == 'create' || mode == 'edit'">
                                <input id="inputAddress" name="address" v-model="user.profile[0].address" type="text" class="form-control" placeholder="@lang('user.fields.address')">
                            </template>
                            <template v-if="mode == 'show'">
                                <div class="form-control-plaintext">@{{ user.profile[0].address }}</div>
                            </template>
                        </div>
                    </div>
                    <div v-bind:class="{ 'form-group row':true, 'is-invalid':errors.has('ic_num') }">
                        <label for="inputICNum" class="col-2 col-form-label">@lang('user.fields.ic_num')</label>
                        <div class="col-md-10">
                            <template v-if="mode == 'create' || mode == 'edit'">
                                <input id="inputICNum" name="ic_num" type="text" class="form-control" placeholder="@lang('user.fields.ic_num')"
                                       v-model="user.profile[0].ic_num" v-validate="'required'" data-vv-as="{{ trans('user.fields.ic_num') }}">
                                <span v-show="errors.has('ic_num')" class="invalid-feedback">@{{ errors.first('ic_num') }}</span>
                            </template>
                            <template v-if="mode == 'show'">
                                <div class="form-control-plaintext">@{{ user.profile[0].ic_num }}</div>
                            </template>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputImage" class="col-2 col-form-label">@lang('user.fields.photo')</label>
                        <div class="col-md-10">
                            <template v-if="mode == 'create' || mode == 'edit'">
                                <img class="img-avatar128" src="http://localhost:8000/images/no_image.png"/>
                                <input type="file" id="inputImage" name="image_filename">
                            </template>
                            <template v-if="mode == 'show'">
                                <div class="form-control-plaintext">
                                    <img class="img-avatar128" src="http://localhost:8000/images/no_image.png"/>
                                </div>
                            </template>
                        </div>
                    </div>
                    <div v-bind:class="{ 'form-group row':true, 'is-invalid':errors.has('email') }">
                        <label for="inputEmail" class="col-2 col-form-label">@lang('user.fields.email')</label>
                        <div class="col-md-10">
                            <template v-if="mode == 'create' || mode == 'edit'">
                                <input id="inputEmail" name="email" type="text" class="form-control" placeholder="@lang('user.fields.email')"
                                       v-model="user.email" v-validate="'required|email'" data-vv-as="{{ trans('user.fields.email') }}">
                                <span v-show="errors.has('email')" class="invalid-feedback">@{{ errors.first('email') }}</span>
                            </template>
                            <template v-if="mode == 'show'">
                                <div class="form-control-plaintext">@{{ user.email }}</div>
                            </template>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputPhoneNumber" class="col-2 col-form-label">@lang('user.fields.phone_number')</label>
                        <div class="col-md-10">
                            <table class="table table-bordered">
                                <thead class="thead-light">
                                    <tr>
                                        <th>@lang('user.index.table.table_phone.header.provider')</th>
                                        <th>@lang('user.index.table.table_phone.header.number')</th>
                                        <th>@lang('user.index.table.table_phone.header.remarks')</th>
                                        <th class="text-center">@lang('labels.ACTION')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(ph, phIdx) in user.profile[0].phone_numbers">
                                        <td v-bind:class="{ 'is-invalid':errors.has('phoneprovider_' + phIdx) }">
                                            <input type="hidden" name="phone_numbers_id[]" v-model="ph.hId"/>
                                            <template v-if="mode == 'create' || mode == 'edit'">
                                                <select name="phone_provider_id[]'" class="form-control" v-model="ph.phoneProviderHId"
                                                        v-validate="'required'" v-bind:data-vv-as="'{{ trans('user.index.table.table_phone.header.provider') }} ' + (phIdx + 1)"
                                                        v-bind:data-vv-name="'phoneprovider_' + phIdx">
                                                    <option v-bind:value="defaultPleaseSelect">@lang('labels.PLEASE_SELECT')</option>
                                                    <option v-for="(p, pIdx) in providerDDL" v-bind:value="p.hId">@{{ p.name }} (@{{ p.short_name }})</option>
                                                </select>
                                            </template>
                                            <template v-if="mode == 'show'">
                                                <div class="form-control-plaintext">@{{ ph.provider ? ph.provider.fullName:'' }}</div>
                                            </template>
                                        </td>
                                        <td v-bind:class="{ 'is-invalid':errors.has('number_' + phIdx) }">
                                            <template v-if="mode == 'create' || mode == 'edit'">
                                                <input type="text" name="phone_number[]" class="form-control" v-model="ph.number"
                                                       v-validate="'required'" v-bind:data-vv-as="'{{ trans('user.index.table.table_phone.header.number') }} ' + (phIdx + 1)"
                                                       v-bind:data-vv-name="'number_' + phIdx">
                                            </template>
                                            <template v-if="mode == 'show'">
                                                <div class="form-control-plaintext">@{{ ph.number }}</div>
                                            </template>
                                        </td>
                                        <td>
                                            <template v-if="mode == 'create' || mode == 'edit'">
                                                <input type="text" class="form-control" name="remarks[]" v-model="ph.remarks">
                                            </template>
                                            <template v-if="mode == 'show'">
                                                <div class="form-control-plaintext">@{{ ph.remarks }}</div>
                                            </template>
                                        </td>
                                        <td class="text-center">
                                            <template v-if="mode == 'create' || mode == 'edit'">
                                                <button type="button" class="btn btn-sm btn-danger" v-bind:data="phIdx" v-on:click="removeSelectedPhone(phIdx)">
                                                    <span class="fa fa-close fa-fw"></span>
                                                </button>
                                            </template>
                                            <template v-if="mode == 'show'">
                                                <div class="form-control-plaintext">&nbsp;</div>
                                            </template>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <template v-if="mode == 'create' || mode == 'edit'">
                                <button type="button" class="btn btn-sm btn-default" v-on:click="addNewPhone">@lang('buttons.create_new_button')</button>
                            </template>
                            <template v-if="mode == 'show'">
                                <div class="form-control-plaintext">&nbsp;</div>
                            </template>
                        </div>
                    </div>
                    <div v-bind:class="{ 'form-group row':true, 'is-invalid':errors.has('company') }">
                        <label for="inputCompany" class="col-2 col-form-label">@lang('user.fields.company')</label>
                        <div class="col-md-10">
                            <template v-if="mode == 'create' || mode == 'edit'">
                                <select class="form-control"
                                        name="company"
                                        v-model="user.companyHId"
                                        v-validate="'required'"
                                        data-vv-as="{{ trans('user.fields.company') }}">
                                    <option v-bind:value="defaultPleaseSelect">@lang('labels.PLEASE_SELECT')</option>
                                    <option v-for="(c, cI) in companyDDL" v-bind:value="c.hId">@{{ c.name }}</option>
                                </select>
                                <span v-show="errors.has('company')" class="invalid-feedback">@{{ errors.first('company') }}</span>
                            </template>
                            <template v-if="mode == 'show'">
                                <div class="form-control-plaintext">@{{ user.company ? user.company.name:'' }}</div>
                            </template>
                        </div>
                    </div>
                    <div v-bind:class="{ 'form-group row':true, 'is-invalid':errors.has('roles') }">
                        <label for="inputRoles" class="col-2 col-form-label">@lang('user.fields.roles')</label>
                        <div class="col-md-10">
                            <template v-if="mode == 'create' || mode == 'edit'">
                                <select class="form-control"
                                        name="roles"
                                        v-model="user.roleHId"
                                        v-validate="'required'"
                                        data-vv-as="{{ trans('user.fields.roles') }}">
                                    <option v-bind:value="defaultPleaseSelect">@lang('labels.PLEASE_SELECT')</option>
                                    <option v-for="(r, rI) in rolesDDL" v-bind:value="r.hId">@{{ r.display_name }}</option>
                                </select>
                                <span v-show="errors.has('roles')" class="invalid-feedback">@{{ errors.first('roles') }}</span>
                            </template>
                            <template v-if="mode == 'show'">
                                <div class="form-control-plaintext">@{{ user.roles[0].name }}</div>
                            </template>
                        </div>
                    </div>
                    <div v-bind:class="{ 'form-group row':true, 'is-invalid':errors.has('active_lookup') }">
                        <label for="inputActive" class="col-2 col-form-label">@lang('user.fields.active')</label>
                        <div class="col-md-10">
                            <input type="hidden" name="active" v-model="user.active"/>
                            <template v-if="mode == 'create' || mode == 'edit'">
                                <select class="form-control"
                                        name="active_lookup"
                                        v-model="user.activeLookup"
                                        v-validate="'required'"
                                        data-vv-as="{{ trans('user.fields.active') }}"
                                        v-on:change="setActiveFlag">
                                    <option v-bind:value="defaultPleaseSelect">@lang('labels.PLEASE_SELECT')</option>
                                    <option v-for="(yn, ynI) in yesnoDDL" v-bind:value="yn.code">@{{ yn.description }}</option>
                                </select>
                                <span v-show="errors.has('active_lookup')" class="invalid-feedback">@{{ errors.first('active_lookup') }}</span>
                            </template>
                            <template v-if="mode == 'show'">
                                <div class="form-control-plaintext">@{{ user.activeI18n }}</div>
                            </template>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputPassword" class="col-2 col-form-label">@lang('user.fields.password')</label>
                        <div class="col-md-10">
                            <template v-if="mode == 'create' || mode == 'edit'">
                                <input id="inputPassword" name="password" type="password" class="form-control" placeholder="@lang('user.fields.password')" v-model="user.password">
                            </template>
                            <template v-if="mode == 'show'">
                                <div class="form-control-plaintext">************************</div>
                            </template>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputPasswordConfirmation" class="col-2 col-form-label">@lang('user.fields.retype_password')</label>
                        <div class="col-md-10">
                            <template v-if="mode == 'create' || mode == 'edit'">
                                <input id="inputPasswordConfirmation" name="password_confirmation" type="password" class="form-control" placeholder="@lang('user.fields.retype_password')" v-model="user.password_confirmation">
                            </template>
                            <template v-if="mode == 'show'">
                                <div class="form-control-plaintext">************************</div>
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
    @routes('user')
@endsection

@section('custom_js')
    <script type="application/javascript">

    </script>
    <script type="application/javascript" src="{{ mix('js/apps/user.js') }}"></script>
@endsection
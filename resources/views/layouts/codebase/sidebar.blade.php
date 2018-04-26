<nav id="sidebar">
    <div id="sidebar-scroll">
        <div class="sidebar-content">
            <div class="content-header content-header-fullrow px-15">
                <div class="content-header-section sidebar-mini-visible-b">
                    <span class="content-header-item font-w700 font-size-xl float-left animated fadeIn">
                        <i class="fa fa-home fa-fw"></i>
                    </span>
                </div>

                <div class="content-header-section text-center align-parent sidebar-mini-hidden">
                    <button type="button" class="btn btn-circle btn-dual-secondary d-lg-none align-v-r" data-toggle="layout" data-action="sidebar_close">
                        <i class="fa fa-times text-danger"></i>
                    </button>

                    <div class="content-header-item">
                        <div class="{{ mt_rand(1, 5) == 1 ? 'animated flip':'' }}">
                            <a class="link-effect font-w700" href="{{ route('db') }}">
                                <span class="font-size-xl text-dual-primary-dark">TK</span><span class="font-size-xl text-primary">BARU</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="content-side content-side-full content-side-user px-10 align-parent">
                <div class="sidebar-mini-visible-b align-v animated fadeIn">
                    <img class="img-avatar img-avatar32" src="{{ asset('images/def-user.png') }}" alt="">
                </div>

                <div class="sidebar-mini-hidden-b text-center">
                    <a class="img-link" href="#">
                        <img class="img-avatar" src="{{ asset('images/def-user.png') }}" alt="">
                    </a>
                    <ul class="list-inline mt-10">
                        <li class="list-inline-item">
                            <a class="link-effect text-dual-primary-dark font-size-xs font-w600 text-uppercase" href="#">{{ strtoupper(Auth::user()->roles()->first()->display_name) }}</a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="content-side content-side-full">
                <ul class="nav-main">
                    @permission('menu-po|menu-po_payment|menu-po_copy')
                        <li>
                            <a class="nav-submenu" data-toggle="nav-submenu" href="#"><i class="fa fa-cart-plus fa-fw"></i><span class="sidebar-mini-hide">@lang('sidebar.menu.po')</span></a>
                            <ul>
                                <li>
                                    <a href="{{ route('db.po') }}"><span class="fa fa-cart-plus fa-fw"></span>&nbsp;&nbsp;@lang('sidebar.menu.po')</a>
                                </li>
                                <li>
                                    <a href="#"><span class="fa fa-calculator fa-fw"></span>&nbsp;&nbsp;@lang('sidebar.menu.po.payment')</a>
                                </li>
                                <li>
                                    <a href="#"><span class="fa fa-copy fa-rotate-180 fa-fw"></span>&nbsp;&nbsp;@lang('sidebar.menu.po.copy')</a>
                                </li>
                            </ul>
                        </li>
                    @endpermission
                    @permission('menu-po|menu-po_payment|menu-po_copy')
                        <li>
                            <a class="nav-submenu" data-toggle="nav-submenu" href="#"><i class="fa fa-cart-arrow-down fa-fw"></i><span class="sidebar-mini-hide">@lang('sidebar.menu.so')</span></a>
                            <ul>
                                <li>
                                    <a href="#"><span class="fa fa-cart-arrow-down fa-fw"></span>&nbsp;&nbsp;@lang('sidebar.menu.so')</a>
                                </li>
                                <li>
                                    <a href="#"><span class="fa fa-calculator fa-fw"></span>&nbsp;&nbsp;@lang('sidebar.menu.so.payment')</a>
                                </li>
                                <li>
                                    <a href="#"><span class="fa fa-copy fa-fw"></span>&nbsp;&nbsp;@lang('sidebar.menu.so.copy')</a>
                                </li>
                            </ul>
                        </li>
                    @endpermission
                    @permission('menu-price_level')
                        <li>
                            <a class="nav-submenu" data-toggle="nav-submenu" href="#"><i class="fa fa-barcode fa-fw"></i><span class="sidebar-mini-hide">@lang('sidebar.menu.price')</span></a>
                            <ul>
                                <li>
                                    <a href="#"><span class="fa fa-barcode fa-fw"></span>&nbsp;&nbsp;@lang('sidebar.menu.price.todayprice')</a>
                                </li>
                                @permission('menu-price_level')
                                    <li>
                                        <a class="{{ active_class(if_route_pattern('db.price_level') || if_route_pattern('db.price_level.*')) }}" href="{{ route('db.price_level') }}">
                                            <span class="fa fa-table fa-fw"></span>&nbsp;&nbsp;@lang('sidebar.menu.price.pricelevel')
                                        </a>
                                    </li>
                                @endpermission
                            </ul>
                        </li>
                    @endpermission
                    <li>
                        <a class="nav-submenu" data-toggle="nav-submenu" href="#"><i class="fa fa-wrench fa-fw"></i><span class="sidebar-mini-hide">@lang('sidebar.menu.warehouse')</span></a>
                        @permission('menu-warehouse')
                            <ul>
                                @permission('menu-warehouse')
                                    <li>
                                        <a class="{{ active_class(if_route_pattern('db.warehouse') || if_route_pattern('db.warehouse.*')) }}" href="{{ route('db.warehouse') }}">
                                            <span class="fa fa-wrench fa-fw"></span>&nbsp;&nbsp;@lang('sidebar.menu.warehouse')
                                        </a>
                                    </li>
                                @endpermission
                                <li>
                                    <a href="#"><span class="fa fa-mail-forward fa-rotate-90 fa-fw"></span>&nbsp;&nbsp;@lang('sidebar.menu.warehouse.inflow')</a>
                                </li>
                                <li>
                                    <a href="#"><span class="fa fa-mail-reply fa-rotate-90 fa-fw"></span>&nbsp;&nbsp;@lang('sidebar.menu.warehouse.outflow')</a>
                                </li>
                                <li>
                                    <a class="nav-submenu" data-toggle="nav-submenu" href="#"><span class="fa fa-database fa-fw"></span>&nbsp;&nbsp;@lang('sidebar.menu.warehouse.stock')</a>
                                    <ul>
                                        <li>
                                            <a href="#"><span class="fa fa-database fa-fw"></span>&nbsp;&nbsp;@lang('sidebar.menu.warehouse.stock.opname')</a>
                                        </li>
                                        <li>
                                            <a href="#"><span class="fa fa-refresh fa-fw"></span>&nbsp;&nbsp;@lang('sidebar.menu.warehouse.stock.transfer')</a>
                                        </li>
                                        <li>
                                            <a href="#"><span class="fa fa-sort-amount-asc fa-fw"></span>&nbsp;&nbsp;@lang('sidebar.menu.warehouse.stock.merger')</a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        @endpermission
                    </li>
                    @permission('menu-truck|menu-vendor_trucking')
                        <li>
                            <a class="nav-submenu" data-toggle="nav-submenu" href="#"><i class="fa fa-truck fa-fw"></i><span class="sidebar-mini-hide">@lang('sidebar.menu.truck')</span></a>
                            <ul>
                                @permission('menu-truck')
                                    <li>
                                        <a class="{{ active_class(if_route_pattern('db.truck') || if_route_pattern('db.truck.*')) }}" href="{{ route('db.truck') }}">
                                            <span class="fa fa-truck fa-fw"></span>&nbsp;&nbsp;@lang('sidebar.menu.truck')
                                        </a>
                                    </li>
                                @endpermission
                                @permission('menu-vendor_trucking')
                                    <li>
                                        <a class="{{ active_class(if_route_pattern('db.truck.vendor_trucking') || if_route_pattern('db.truck.vendor_trucking.*')) }}" href="{{ route('db.truck.vendor_trucking') }}">
                                            <span class="fa fa-ge fa-fw"></span>&nbsp;&nbsp;@lang('sidebar.menu.truck.vendor')
                                        </a>
                                    </li>
                                @endpermission
                                <li>
                                    <a href=""><span class="fa fa-ambulance fa-fw"></span>&nbsp;&nbsp;@lang('sidebar.menu.truck.maintenance')</a>
                                </li>
                            </ul>
                        </li>
                    @endpermission
                    @permission('menu-bank')
                        <li>
                            <a class="nav-submenu" data-toggle="nav-submenu" href="#"><i class="fa fa-bank fa-fw"></i><span class="sidebar-mini-hide">@lang('sidebar.menu.bank')</span></a>
                            <ul>
                                @permission('menu-bank')
                                    <li>
                                        <a href="{{ route('db.bank') }}">
                                            <span class="fa fa-bank fa-fw"></span>&nbsp;&nbsp;@lang('sidebar.menu.bank')
                                        </a>
                                    </li>
                                @endpermission
                                <li>
                                    <a href="#"><span class="fa fa-cloud-upload fa-fw"></span>&nbsp;&nbsp;@lang('sidebar.menu.bank.upload')</a>
                                </li>
                                <li>
                                    <a href="#"><span class="fa fa-compress fa-fw"></span>&nbsp;&nbsp;@lang('sidebar.menu.bank.consolidate')</a>
                                </li>
                            </ul>
                        </li>
                    @endpermission
                    <li>
                        <a class="nav-submenu" data-toggle="nav-submenu" href="#"><i class="fa fa-gavel fa-fw"></i><span class="sidebar-mini-hide">@lang('sidebar.menu.tax')</span></a>
                        <ul>
                            <li>
                                <a class="nav-submenu" data-toggle="nav-submenu" href="#"><span class="fa fa-database fa-fw"></span>&nbsp;&nbsp;@lang('sidebar.menu.tax.invoice')</a>
                                <ul>
                                    <li>
                                        <a href="#"><span class="fa fa-arrow-left fa-fw"></span>&nbsp;&nbsp;@lang('sidebar.menu.tax.invoice.input')</a>
                                    </li>
                                    <li>
                                        <a href="#"><span class="fa fa-arrow-right fa-fw"></span>&nbsp;&nbsp;@lang('sidebar.menu.tax.invoice.output')</a>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <a href="#"><span class="fa fa-magic fa-fw"></span>&nbsp;&nbsp;@lang('sidebar.menu.tax.generate')</a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a class="nav-submenu" data-toggle="nav-submenu" href="#"><i class="fa fa-odnoklassniki fa-fw"></i><span class="sidebar-mini-hide">@lang('sidebar.menu.employee')</span></a>
                        <ul>
                            <li>
                                <a href="#"><span class="fa fa-odnoklassniki fa-fw"></span>&nbsp;&nbsp;@lang('sidebar.menu.employee.list')</a>
                            </li>
                            <li>
                                <a href="#"><span class="fa fa-money fa-fw"></span>&nbsp;&nbsp;@lang('sidebar.menu.employee.salary')</a>
                            </li>
                        </ul>
                    </li>
                    @permission('menu-product|menu-product_type')
                        <li>
                            <a class="nav-submenu" data-toggle="nav-submenu" href="#"><i class="fa fa-cube fa-fw"></i><span class="sidebar-mini-hide">@lang('sidebar.menu.product')</span></a>
                            <ul>
                                @permission('menu-product')
                                    <li>
                                        <a class="{{ active_class(if_route_pattern('db.product') || if_route_pattern('db.product.*')) }}" href="{{ route('db.product') }}">
                                            <span class="fa fa-cube fa-fw"></span>&nbsp;&nbsp;@lang('sidebar.menu.product')
                                        </a>
                                    </li>
                                @endpermission
                                @permission('menu-product_type')
                                    <li>
                                        <a href="#"><span class="fa fa-cubes fa-fw"></span>&nbsp;&nbsp;@lang('sidebar.menu.product.type')</a>
                                    </li>
                                @endpermission
                            </ul>
                        </li>
                    @endpermission
                    <li>
                        <a class="nav-submenu" data-toggle="nav-submenu" href="#"><i class="fa fa-smile-o fa-fw"></i><span class="sidebar-mini-hide">@lang('sidebar.menu.customer')</span></a>
                        <ul>
                            <li>
                                <a href="#"><span class="fa fa-smile-o fa-fw"></span>&nbsp;&nbsp;@lang('sidebar.menu.customer')</a>
                            </li>
                            <li>
                                <a href="#"><span class="fa fa-check fa-fw"></span>&nbsp;&nbsp;@lang('sidebar.menu.customer.confirmation')</a>
                            </li>
                        </ul>
                    </li>
                    @permission('menu-supplier')
                        <li>
                            <a class="nav-submenu" data-toggle="nav-submenu" href="#"><i class="fa fa-building-o fa-fw"></i><span class="sidebar-mini-hide">@lang('sidebar.menu.supplier')</span></a>
                            <ul>
                                @permission('menu-company')
                                    <li>
                                        <a class="{{ active_class(if_route_pattern('db.supplier') || if_route_pattern('db.supplier.*')) }}" href="{{ route('db.supplier') }}">
                                            <span class="fa fa-building-o fa-fw"></span>&nbsp;&nbsp;@lang('sidebar.menu.supplier')
                                        </a>
                                    </li>
                                @endpermission
                            </ul>
                        </li>
                    @endpermission
                    @permission('menu-company|menu-user|menu-user.fields|menu-unit|menu-phone_provider')
                        <li class="{{ active_class(if_route_pattern('db.settings.*'), 'open') }}">
                            <a class="nav-submenu" data-toggle="nav-submenu" href="#"><i class="fa fa-cog fa-fw"></i><span class="sidebar-mini-hide">@lang('sidebar.menu.settings')</span></a>
                            <ul>
                                @permission('menu-company')
                                    <li>
                                        <a class="{{ active_class(if_route_pattern('db.settings.company') || if_route_pattern('db.settings.company.*')) }}" href="{{ route('db.settings.company') }}">
                                            <span class="fa fa-umbrella fa-fw"></span>&nbsp;&nbsp;@lang('sidebar.menu.settings.company')
                                        </a>
                                    </li>
                                @endpermission
                                @permission('menu-users')
                                    <li>
                                        <a class="{{ active_class(if_route_pattern('db.settings.user') || if_route_pattern('db.settings.user.*')) }}" href="{{ route('db.settings.user') }}">
                                            <span class="fa fa-user fa-fw"></span>&nbsp;&nbsp;@lang('sidebar.menu.settings.user')
                                        </a>
                                    </li>
                                @endpermission
                                @permission('menu-role')
                                    <li>
                                        <a class="{{ active_class(if_route_pattern('db.settings.role') || if_route_pattern('db.settings.role.*')) }}" href="{{ route('db.settings.role') }}">
                                            <span class="fa fa-key fa-fw"></span>&nbsp;&nbsp;@lang('sidebar.menu.settings.role')
                                        </a>
                                    </li>
                                @endpermission
                                @permission('menu-unit')
                                    <li>
                                        <a class="{{ active_class(if_route_pattern('db.settings.unit') || if_route_pattern('db.settings.unit.*')) }}" href="{{ route('db.settings.unit') }}"><span class="fa fa-bolt fa-fw"></span>&nbsp;&nbsp;@lang('sidebar.menu.settings.unit')</a>
                                    </li>
                                @endpermission
                                @permission('menu-phoneprovider')
                                    <li>
                                        <a class="{{ active_class(if_route_pattern('db.settings.phone_provider') || if_route_pattern('db.settings.phone_provider.*')) }}"href="{{ route('db.settings.phone_provider') }}"><span class="fa fa-phone-square fa-fw"></span>&nbsp;&nbsp;@lang('sidebar.menu.settings.phone_provider')</a>
                                    </li>
                                @endpermission
                            </ul>
                        </li>
                    @endpermission
                </ul>
            </div>
        </div>
    </div>
</nav>
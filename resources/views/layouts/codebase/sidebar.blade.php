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
                    <li>
                        <a class="nav-submenu" data-toggle="nav-submenu" href="#"><i class="fa fa-cart-plus fa-fw"></i><span class="sidebar-mini-hide">@lang('sidebar.menu.po')</span></a>
                        <ul>
                            <li>
                                <a href="#"><span class="fa fa-cart-plus fa-fw"></span>&nbsp;&nbsp;@lang('sidebar.menu.po.new')</a>
                            </li>
                            <li>
                                <a href="#"><span class="fa fa-code-fork fa-rotate-180 fa-fw"></span>&nbsp;&nbsp;@lang('sidebar.menu.po.revise')</a>
                            </li>
                            <li>
                                <a href="#"><span class="fa fa-calculator fa-fw"></span>&nbsp;&nbsp;@lang('sidebar.menu.po.payment')</a>
                            </li>
                            <li>
                                <a href="#"><span class="fa fa-copy fa-rotate-180 fa-fw"></span>&nbsp;&nbsp;@lang('sidebar.menu.po.copy')</a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a class="nav-submenu" data-toggle="nav-submenu" href="#"><i class="fa fa-cart-arrow-down fa-fw"></i><span class="sidebar-mini-hide">@lang('sidebar.menu.so')</span></a>
                        <ul>
                            <li>
                                <a href="#"><span class="fa fa-cart-arrow-down fa-fw"></span>&nbsp;&nbsp;@lang('sidebar.menu.so.new')</a>
                            </li>
                            <li>
                                <a href="#"><span class="fa fa-code-fork fa-fw"></span>&nbsp;&nbsp;@lang('sidebar.menu.so.revise')</a>
                            </li>
                            <li>
                                <a href="#"><span class="fa fa-calculator fa-fw"></span>&nbsp;&nbsp;@lang('sidebar.menu.so.payment')</a>
                            </li>
                            <li>
                                <a href="#"><span class="fa fa-copy fa-fw"></span>&nbsp;&nbsp;@lang('sidebar.menu.so.copy')</a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a class="nav-submenu" data-toggle="nav-submenu" href="#"><i class="fa fa-barcode fa-fw"></i><span class="sidebar-mini-hide">@lang('sidebar.menu.price')</span></a>
                        <ul>
                            <li>
                                <a href="#"><span class="fa fa-barcode fa-fw"></span>&nbsp;&nbsp;@lang('sidebar.menu.price.todayprice')</a>
                            </li>
                            <li>
                                <a href="#"><span class="fa fa-table fa-fw"></span>&nbsp;&nbsp;@lang('sidebar.menu.price.pricelevel')</a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a class="nav-submenu" data-toggle="nav-submenu" href="#"><i class="fa fa-wrench fa-fw"></i><span class="sidebar-mini-hide">@lang('sidebar.menu.warehouse')</span></a>
                        <ul>
                            <li>
                                <a href="#"><span class="fa fa-wrench fa-fw"></span>&nbsp;&nbsp;@lang('sidebar.menu.warehouse')</a>
                            </li>
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
                    </li>
                    <li>
                        <a class="nav-submenu" data-toggle="nav-submenu" href="#"><i class="fa fa-truck fa-fw"></i><span class="sidebar-mini-hide">@lang('sidebar.menu.truck')</span></a>
                        <ul>
                            <li>
                                <a href="#"><span class="fa fa-truck fa-fw"></span>&nbsp;&nbsp;@lang('sidebar.menu.truck')</a>
                            </li>
                            <li>
                                <a href="#"><span class="fa fa-ge fa-fw"></span>&nbsp;&nbsp;@lang('sidebar.menu.truck.vendor')</a>
                            </li>
                            <li>
                                <a href="#"><span class="fa fa-ambulance fa-fw"></span>&nbsp;&nbsp;@lang('sidebar.menu.truck.maintenance')</a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a class="nav-submenu" data-toggle="nav-submenu" href="#"><i class="fa fa-bank fa-fw"></i><span class="sidebar-mini-hide">@lang('sidebar.menu.bank')</span></a>
                        <ul>
                            <li>
                                <a href="#"><span class="fa fa-bank fa-fw"></span>&nbsp;&nbsp;@lang('sidebar.menu.bank')</a>
                            </li>
                            <li>
                                <a href="#"><span class="fa fa-ge fa-fw"></span>&nbsp;&nbsp;@lang('sidebar.menu.truck.vendor')</a>
                            </li>
                            <li>
                                <a href="#"><span class="fa fa-ambulance fa-fw"></span>&nbsp;&nbsp;@lang('sidebar.menu.truck.maintenance')</a>
                            </li>
                        </ul>
                    </li>
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
                    <li>
                        <a class="nav-submenu" data-toggle="nav-submenu" href="#"><i class="fa fa-cube fa-fw"></i><span class="sidebar-mini-hide">@lang('sidebar.menu.product')</span></a>
                        <ul>
                            <li>
                                <a href="#"><span class="fa fa-cube fa-fw"></span>&nbsp;&nbsp;@lang('sidebar.menu.product')</a>
                            </li>
                            <li>
                                <a href="#"><span class="fa fa-cubes fa-fw"></span>&nbsp;&nbsp;@lang('sidebar.menu.product.type')</a>
                            </li>
                        </ul>
                    </li>
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
                    <li>
                        <a class="nav-submenu" data-toggle="nav-submenu" href="#"><i class="fa fa-building-o fa-fw"></i><span class="sidebar-mini-hide">@lang('sidebar.menu.supplier')</span></a>
                        <ul>
                            <li>
                                <a href="#"><span class="fa fa-building-o fa-fw"></span>&nbsp;&nbsp;@lang('sidebar.menu.supplier')</a>
                            </li>
                        </ul>
                    </li>
                    <li class="{{ active_class(if_route_pattern('db.settings.*'), 'open') }}">
                        <a class="nav-submenu" data-toggle="nav-submenu" href="#"><i class="fa fa-cog fa-fw"></i><span class="sidebar-mini-hide">@lang('sidebar.menu.settings')</span></a>
                        <ul>
                            <li>
                                <a class="{{ active_class(if_route_pattern('db.settings.company') || if_route_pattern('db.settings.company.*')) }}" href="{{ route('db.settings.company') }}">
                                    <span class="fa fa-umbrella fa-fw"></span>&nbsp;&nbsp;@lang('sidebar.menu.settings.company')
                                </a>
                            </li>
                            <li>
                                <a href="#"><span class="fa fa-user fa-fw"></span>&nbsp;&nbsp;@lang('sidebar.menu.settings.user')</a>
                            </li>
                            <li>
                                <a href="#"><span class="fa fa-key fa-fw"></span>&nbsp;&nbsp;@lang('sidebar.menu.settings.roles')</a>
                            </li>
                            <li>
                                <a href="#"><span class="fa fa-bolt fa-fw"></span>&nbsp;&nbsp;@lang('sidebar.menu.settings.unit')</a>
                            </li>
                            <li>
                                <a href="#"><span class="fa fa-money fa-fw"></span>&nbsp;&nbsp;@lang('sidebar.menu.settings.currencies')</a>
                            </li>
                            <li>
                                <a href="#"><span class="fa fa-phone-square fa-fw"></span>&nbsp;&nbsp;@lang('sidebar.menu.settings.phone_provider')</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>
<header id="page-header">
    <div class="content-header">
        <div class="content-header-section">
            <button type="button" class="btn btn-circle btn-dual-secondary" data-toggle="layout" data-action="sidebar_toggle">
                <i class="fa fa-navicon"></i>
            </button>

            <button type="button" class="btn btn-circle btn-dual-secondary" data-toggle="layout" data-action="header_search_on">
                <i class="fa fa-search"></i>
            </button>
        </div>

        <div class="content-header-section">
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-rounded btn-dual-secondary disabled" id="timeoutCount" data-toggle="tooltip" data-placement="bottom" title="Timeout Counter">
                    00:00:00
                </button>
                <a class="btn btn-rounded btn-dual-secondary" data-toggle="tooltip" data-placement="bottom" title="Back To Frontpage">
                    <span class="fa fa-external-link fa-fw" title="Back To Frontpage"></span>
                </a>
                <button type="button" class="btn btn-rounded btn-dual-secondary" id="page-header-language-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fa fa-globe"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-right min-width-150" aria-labelledby="page-header-language-dropdown">
                    <a class="dropdown-item" href="#">
                        <strong>English</strong>
                    </a>
                    <a class="dropdown-item" href="#" data-toggle="layout" data-action="side_overlay_toggle">
                        Indonesia
                    </a>
                </div>
                <a href="/logout" class="btn btn-rounded btn-dual-secondary" data-toggle="tooltip" data-placement="bottom" title="Logout" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fa fa-sign-out fa-fw"></i>
                </a>
                <form id="logout-form" action="{{ url('/logout') }}" method="POST">{{ csrf_field() }}</form>
            </div>

            <div class="btn-group" role="group">
                <button type="button" class="btn btn-rounded btn-dual-secondary" id="page-header-company-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    {{ Auth::user()->company->name }}<i class="fa fa-angle-down ml-5"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-right min-width-150" aria-labelledby="page-header-company-dropdown">
                    <div class="row">
                        <div class="col-12 text-center">
                            <img class="img-thumbnail" src="{{ empty(Auth::user()->company->image_filename) ? asset('images/def-images.png') : asset('images/'.Auth::user()->company->image_filename) }}"/>
                        </div>
                    </div>
                    <h6 class="dropdown-header text-center">{{ Auth::user()->company->name }}</h6>
                    <p class="text-center">
                        <small>{{ Auth::user()->company->address }}</small><br/>
                        <small>{{ Auth::user()->company->tax_id }}</small>
                    </p>
                </div>
            </div>

            <button type="button" class="btn btn-circle btn-dual-secondary" data-toggle="layout" data-action="side_overlay_toggle">
                <i class="fa fa-tasks"></i>
            </button>
        </div>
    </div>

    <div id="page-header-search" class="overlay-header">
        <div class="content-header content-header-fullrow">
            <form action="#" method="post">
                <div class="input-group">
                    <span class="input-group-btn">
                        <button type="button" class="btn btn-secondary" data-toggle="layout" data-action="header_search_off">
                            <i class="fa fa-times"></i>
                        </button>
                    </span>
                    <input type="text" class="form-control" placeholder="Search or hit ESC.." id="page-header-search-input" name="page-header-search-input">
                    <span class="input-group-btn">
                        <button type="submit" class="btn btn-secondary">
                            <i class="fa fa-search"></i>
                        </button>
                    </span>
                </div>
            </form>
        </div>
    </div>

    <div id="page-header-loader" class="overlay-header bg-primary">
        <div class="content-header content-header-fullrow text-center">
            <div class="content-header-item">
                <i class="fa fa-sun-o fa-spin text-white"></i>
            </div>
        </div>
    </div>
</header>
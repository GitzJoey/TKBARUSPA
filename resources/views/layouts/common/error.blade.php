<div class="alert alert-danger alert-dismissable" role="alert" v-show="errors.count() > 0">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true"><i class="fa fa-close fa-fw"></i></span>
    </button>
    <h3 class="alert-heading font-size-h4 font-w400">
        @lang('labels.GENERAL_ERROR_TITLE')
    </h3>
    <p>@lang('labels.GENERAL_ERROR_DESC')</p>
    <ul v-for="(e, eIdx) in errors.all()"><li>@{{ e }}</li></ul>
</div>

<div class="alert alert-danger" role="alert" v-show="errors.count() > 0">
    <h3 class="alert-heading font-size-h4 font-w400">
        <i class="si si-exclamation"></i>&nbsp;@lang('labels.GENERAL_ERROR_TITLE')
    </h3>
    <p>@lang('labels.GENERAL_ERROR_DESC')</p>
    <ul v-for="(e, eIdx) in errors.all()"><li>@{{ e }}</li></ul>
</div>

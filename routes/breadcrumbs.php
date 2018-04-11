<?php
/**
 * Created by PhpStorm.
 * User: miftah.fathudin
 * Date: 10/29/2016
 * Time: 10:50 AM
 */

Breadcrumbs::register('dashboard', function ($breadcrumbs){
    $breadcrumbs->push(trans('breadcrumb.dashboard'), route('db'));
});

Breadcrumbs::register('product', function($breadcrumbs){
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push(trans('breadcrumb.product'), route('db.product'));
});

Breadcrumbs::register('settings_company', function($breadcrumbs){
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push(trans('breadcrumb.settings'));
    $breadcrumbs->push(trans('breadcrumb.settings.company'), route('db.settings.company'));
});

Breadcrumbs::register('search', function ($breadcrumbs){
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push(trans('breadcrumb.search'), route('db.search'));
});
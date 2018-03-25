<?php
/**
 * Created by PhpStorm.
 * User: Sugito
 * Date: 12/17/2016
 * Time: 11:42 AM
 */

namespace App\Traits;

use App\Scopes\CompanyFilterScope;

trait CompanyFilter
{
    /**
     * Boot the company default filtering trait for model
     *
     * @return void
     */
    public static function bootCompanyFilter()
    {
        static::addGlobalScope(new CompanyFilterScope);
    }
}
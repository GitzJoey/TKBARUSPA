<?php
/**
 * Created by PhpStorm.
 * User: gitzj
 * Date: 5/23/2018
 * Time: 10:56 AM
 */

namespace App\Traits;

use App\Scopes\CurrentStockFilterScope;

trait CurrentStockFilter
{
    public static function bootCurrentStockFilter()
    {
        static::addGlobalScope(new CurrentStockFilterScope);
    }
}
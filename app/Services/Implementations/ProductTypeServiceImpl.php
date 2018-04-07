<?php
/**
 * Created by PhpStorm.
 * User: gitzj
 * Date: 4/8/2018
 * Time: 3:29 AM
 */

namespace App\Services\Implementations;

use Config;

use App\Models\ProductType;

use App\Services\ProductTypeService;

class ProductTypeServiceImpl implements ProductTypeService
{

    public function readAll($limit = 0)
    {
        return ProductType::get();
    }
}

<?php
/**
 * Created by PhpStorm.
 * User: gitzj
 * Date: 5/17/2018
 * Time: 1:47 PM
 */

namespace App\Services\Implementations;

use App\Models\Stock;
use App\Models\StockFlow;

use DB;
use Config;
use Exception;
use LaravelLocalization;

use App\Services\StockService;

class StockServiceImpl implements StockService
{
    public function createPOStockIn(
        $companyId,
        $poId,
        $warehouseId,
        $productId,
        $baseProductUnitId,
        $displayProductUnitId,
        $quantity
    )
    {

    }

    public function createSOStockOut(
        $companyId,
        $soId,
        $warehouseId,
        $productId,
        $baseProductUnitId,
        $displayProductUnitId,
        $quantity
    )
    {

    }
}
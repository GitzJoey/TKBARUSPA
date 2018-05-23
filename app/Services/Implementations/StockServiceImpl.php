<?php
/**
 * Created by PhpStorm.
 * User: gitzj
 * Date: 5/17/2018
 * Time: 1:47 PM
 */

namespace App\Services\Implementations;

use App\Models\Stock;

use App\Services\StockService;

class StockServiceImpl implements StockService
{
    public function createPOStockFlowIn(
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

    public function createPOReStockFlowIn(
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

    public function createSOStockFlowOut(
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

    public function createSOReStockFlowOut(
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
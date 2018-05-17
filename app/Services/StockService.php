<?php
/**
 * Created by PhpStorm.
 * User: gitzj
 * Date: 5/17/2018
 * Time: 1:45 PM
 */

namespace App\Services;

interface StockService
{
    public function createPOStockIn(
        $companyId,
        $poId,
        $warehouseId,
        $productId,
        $baseProductUnitId,
        $displayProductUnitId,
        $quantity
    );

    public function createSOStockOut(
        $companyId,
        $soId,
        $warehouseId,
        $productId,
        $baseProductUnitId,
        $displayProductUnitId,
        $quantity
    );
}
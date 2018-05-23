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
    public function createPOStockFlowIn(
        $companyId,
        $poId,
        $warehouseId,
        $productId,
        $baseProductUnitId,
        $displayProductUnitId,
        $quantity
    );

    public function createPOReStockFlowIn(
        $companyId,
        $poId,
        $warehouseId,
        $productId,
        $baseProductUnitId,
        $displayProductUnitId,
        $quantity
    );

    public function createSOStockFlowOut(
        $companyId,
        $soId,
        $warehouseId,
        $productId,
        $baseProductUnitId,
        $displayProductUnitId,
        $quantity
    );

    public function createSOReStockFlowOut(
        $companyId,
        $soId,
        $warehouseId,
        $productId,
        $baseProductUnitId,
        $displayProductUnitId,
        $quantity
    );
}
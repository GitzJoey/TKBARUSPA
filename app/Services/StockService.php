<?php
/**
 * Created by PhpStorm.
 * User: gitzj
 * Date: 5/17/2018
 * Time: 1:45 PM
 */

namespace App\Services;

use App\Models\Receipt;

interface StockService
{
    public function addStockByReceipt(Receipt $r);

    public function subtractStockByDeliver();

    public function getAllCurrentStock($warehouseId = '');

    public function getStockByProduct();

    public function adjustStockByOpname($companyId, $stockId, $opnameDate, $isMatch, $newQuantity, $reason);
}
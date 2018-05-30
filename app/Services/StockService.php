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

    public function substractStockByDeliver();

    public function getAllCurrentStock($warehouseId = '');
}
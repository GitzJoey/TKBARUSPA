<?php
/**
 * Created by PhpStorm.
 * User: gitzj
 * Date: 3/29/2018
 * Time: 9:29 PM
 */

namespace App\Services;

interface PurchaseOrderService
{
    public function create(
        $name,
        $symbol,
        $status,
        $remarks
    );
    public function read();
    public function update(
        $id,
        $name,
        $symbol,
        $status,
        $remarks
    );

    public function generatePOCode();

    public function getLastPODates($limit);

    public function searchPOByDate($date);
}
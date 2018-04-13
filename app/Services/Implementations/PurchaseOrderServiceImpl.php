<?php
/**
 * Created by PhpStorm.
 * User: TKBARU
 * Date: 4/13/2018
 * Time: 9:37 PM
 */

namespace App\Services\Implementations;

use App\Models\PurchaseOrder;

use App\Services\PurchaseOrderService;

class PurchaseOrderServiceImpl implements PurchaseOrderService
{

    public function create(
        $name,
        $symbol,
        $status,
        $remarks
    )
    {
        // TODO: Implement create() method.
    }

    public function read()
    {
        // TODO: Implement read() method.
    }

    public function update(
        $id,
        $name,
        $symbol,
        $status,
        $remarks
    )
    {
        // TODO: Implement update() method.
    }
}
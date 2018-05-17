<?php
/**
 * Created by PhpStorm.
 * User: gitzj
 * Date: 3/29/2018
 * Time: 9:35 PM
 */

namespace App\Services\Implementations;

use DB;
use Exception;

use App\Models\PurchaseOrder;
use App\Models\Receipt;

use App\Services\PurchaseOrderService;
use App\Services\WarehouseInflowService;

class WarehouseInflowServiceImpl implements WarehouseInflowService
{
    private $purchaseOrderService;

    public function createReceipt(
        $company_id,
        $po_id,
        $receipts,
        $expenses
    )
    {
        DB::beginTransaction();
        try {

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function readReceipt()
    {
        return [];
    }

    public function updateReceipt(
        $id,
        $company_id,
        $name,
        $address,
        $phone_num,
        $status,
        $remarks,
        $sections
    )
    {
    }

    public function deleteReceipt($id)
    {
    }
}
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

use App\Services\WarehouseInflowService;

class WarehouseInflowServiceImpl implements WarehouseInflowService
{
    public function createReceipt(
        $company_id,
        $po_id,
        $receipts,
        $inputtedReceipts,
        $expenses,
        $inputtedExpenses
    )
    {
        DB::beginTransaction();
        try {
            $currentPO = PurchaseOrder::with('receipts')->whereId($po_id);

            $receiptIds = $currentPO->receipts->map(function ($r) {
                return $r->id;
            })->all();

            $receiptsToBeDeleted = array_diff($receiptIds, isset($inputtedReceipts) ? $inputtedReceipts : []);

            Receipt::destroy($receiptsToBeDeleted);

            for ($i = 0; $i < count($receipts); $i++) {
                $r = Receipt::findOrNew($receipts[$i]['receipt_id']);
                $r->receipt_date = $receipts[$i]['receipt_date'];
                $r->conversion_value = $receipts[$i]['conversion_value'];
                $r->brutto = $receipts[$i]['brutto'];
                $r->base_brutto = $receipts[$i]['conversion_value'] * $receipts[$i]['brutto'];
                $r->netto = $receipts[$i]['netto'];
                $r->base_netto = $receipts[$i]['conversion_value'] * $receipts[$i]['netto'];
                $r->tare = $receipts[$i]['tare'];
                $r->base_tare = $receipts[$i]['conversion_value'] * $receipts[$i]['tare'];
                $r->license_plate = $receipts[$i]['license_plate'];
                $r->item_id = $receipts[$i]['item_id'];
                $r->selected_product_unit_id = $receipts[$i]['selected_product_unit_id'];
                $r->base_product_unit_id = $receipts[$i]['base_product_unit_id'];
                $r->company_id = $receipts[$i]['base_product_unit_id'];

                $r->save();
            }

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
<?php
/**
 * Created by PhpStorm.
 * User: gitzj
 * Date: 5/17/2018
 * Time: 1:47 PM
 */

namespace App\Services\Implementations;

use App\Models\Stock;
use App\Models\Receipt;
use App\Models\StockOpname;

use App\Services\StockService;
use Carbon\Carbon;

class StockServiceImpl implements StockService
{
    public function addStockByReceipt(Receipt $r)
    {
        foreach ($r->receiptDetails as $rd) {
            $productId = $rd->item->product->id;
            $receiptDetailId = $rd->id;
            $warehouseId = $rd->receipt()->first()->purchaseOrder()->first()->warehouse_id;

            $sList = Stock::whereProductId($productId)
                ->where('owner_id', '=', $receiptDetailId)
                ->where('owner_type', '=', 'App\Models\ReceiptDetail')->get();

            $baseProductUnitId = $this->getBaseProductUnitId($rd->item->product);
            $displayProductUnitId = $this->getDisplayProductUnitId($rd->item->product);

            if (count($sList) == 0) {
                //NEW
                $stock = new Stock();
                $stock->company_id = $rd->company_id;
                $stock->warehouse_id = $warehouseId;
                $stock->product_id = $productId;
                $stock->base_product_unit_id = $baseProductUnitId;
                $stock->display_product_unit_id = $displayProductUnitId;
                $stock->is_current = 1;
                $stock->quantity_in = $rd->base_netto;
                $stock->quantity_out = 0;
                $stock->quantity_current = $rd->base_netto;

                $rd->stock()->save($stock);
            } else {
                $stockId = 0;
                $this->resetCurrentStock($stockId);

                $stock = new Stock();
                $stock->company_id = $rd->company_id;
                $stock->warehouse_id = '';
                $stock->product_id = $productId;
                $stock->base_product_unit_id = $rd->item->product->base_product_unit_id;
                $stock->display_product_unit_id = $rd->item->product->display_product_unit_id;
                $stock->is_current = 1;
                $stock->quantity_in = $rd->base_netto;
                $stock->quantity_out = 0;
                $stock->quantity_current = $rd->base_netto;

                $rd->stock()->save($stock);
            }
        }
    }

    public function subtractStockByDeliver()
    {

    }

    public function adjustStockByOpname($companyId, $stockId, $opnameDate, $isMatch, $newQuantity, $reason)
    {
        $stock = Stock::find($stockId);

        $qtyMode = $this->determineQtyMode($newQuantity, $stock->quantity_current);
        $diff = abs($newQuantity - $stock->quantity_current);

        $previousQty = $stock->quantity_current;

        $nextStock = new Stock();
        $nextStock->company_id = $stock->company_id;
        $nextStock->warehouse_id = $stock->warehouse_id;
        $nextStock->product_id = $stock->product_id;
        $nextStock->base_product_unit_id = $stock->base_product_unit_id;
        $nextStock->display_product_unit_id = $stock->display_product_unit_id;
        $nextStock->is_current = 1;
        switch (strtoupper($qtyMode)) {
            case 'IN':
                $nextStock->quantity_in = $diff;
                $nextStock->quantity_out = 0;
                $nextStock->quantity_current += $diff;
                $adjustedQty = $nextStock->quantity_current;
                break;
            case 'OUT':
                $nextStock->quantity_in = 0;
                $nextStock->quantity_out = $diff;
                $nextStock->quantity_current -= $diff;
                $adjustedQty = $nextStock->quantity_current;
                break;
            case 'NOCHANGE':
            default:
                $nextStock->quantity_in = $stock->quantity_in;
                $nextStock->quantity_out = $stock->quantity_out;
                $nextStock->quantity_current = $stock->quantity_current;
                $adjustedQty = $nextStock->quantity_current;
                break;
        }

        $stockopname = new StockOpname();
        $stockopname->company_id = $companyId;
        $stockopname->opname_date = $opnameDate;
        $stockopname->is_match = 0;
        $stockopname->previous_quantity = $previousQty;
        $stockopname->adjusted_quantity = $adjustedQty;
        $stockopname->reason = $reason;

        $stockopname->save();
        $stockopname->stock()->save($nextStock);

        $this->resetCurrentStock($stockId);
    }

    public function getAllCurrentStock($warehouseId = '')
    {
        if ($warehouseId == '') {
            return Stock::with('warehouse', 'product')->get();
        } else {
            return Stock::with('warehouse', 'product')->whereWarehouseId($warehouseId)->get();
        }
    }

    public function resetCurrentStock($stockId)
    {
        $stock = Stock::find($stockId);
        $stock->is_current = 0;

        $stock->updated_by = Auth::user()->id;
        $stock->updated_at = Carbon::now();

        $stock->save();
    }

    private function getBaseProductUnitId($product)
    {
        foreach ($product->productUnits as $pu) {
            if ($pu->is_base) {
                return $pu->id;
            }
        }
    }

    private function getDisplayProductUnitId($product)
    {
        foreach ($product->productUnits as $pu) {
            if ($pu->display) {
                return $pu->id;
            }
        }
    }

    private function determineQtyMode($newQty, $oldQty)
    {
        if ($newQty > $oldQty) {
            return 'IN';
        } else if ($newQty < $oldQty) {
            return 'OUT';
        } else {
            return 'NOCHANGE';
        }

    }
}
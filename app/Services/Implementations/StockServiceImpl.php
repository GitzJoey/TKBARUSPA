<?php
/**
 * Created by PhpStorm.
 * User: gitzj
 * Date: 5/17/2018
 * Time: 1:47 PM
 */

namespace App\Services\Implementations;

use App\Models\Stock;
use App\Models\Product;
use App\Models\Receipt;
use App\Models\StockOpname;

use App\Services\StockService;

use Auth;
use Config;
use Carbon\Carbon;

class StockServiceImpl implements StockService
{
    public function addStockByReceipt(Receipt $newReceipt)
    {
        //Get last receipts for this PO
        $lastReceipt = $newReceipt->purchaseOrder->receipts()
            ->where('status', '=', Config::get('lookup.VALUE.RECEIPT_STATUS.PROCESSED'))->latest();

        if ($lastReceipt->count() == 0) {
            $this->addStockByReceipt_NEW($newReceipt);
        } else {
            $this->addStockByReceipt_RESTOCK($newReceipt, $lastReceipt->first());
        }

        $newReceipt->status = Config::get('lookup.VALUE.RECEIPT_STATUS.PROCESSED');
        $newReceipt->save();
    }

    private function addStockByReceipt_NEW(Receipt $r)
    {
        $warehouseId = $r->purchaseOrder()->first()->warehouse_id;

        foreach ($r->receiptDetails as $rd) {
            $baseProductUnitId = $this->getBaseProductUnitId($rd->item->product);
            $displayProductUnitId = $this->getDisplayProductUnitId($rd->item->product);

            $stock = new Stock();
            $stock->company_id = $rd->company_id;
            $stock->warehouse_id = $warehouseId;
            $stock->product_id = $rd->item->product->id;
            $stock->base_product_unit_id = $baseProductUnitId;
            $stock->display_product_unit_id = $displayProductUnitId;
            $stock->is_current = 1;
            $stock->quantity_in = $rd->base_netto;
            $stock->quantity_out = 0;
            $stock->quantity_current = $rd->base_netto;

            $r->stock()->save($stock);
        }
    }

    private function addStockByReceipt_RESTOCK(Receipt $new, Receipt $last)
    {
        $refStockList = Stock::where('owner_id', '=', $last->id)->where('owner_type', '=', 'App\Models\Receipt')->get();

        foreach ($new->receiptDetails as $rd) {
            foreach ($refStockList as $s) {
                if ($rd->item->product_id == $s->product_id) {
                    $stock = new Stock();
                    $stock->company_id = $s->company_id;
                    $stock->warehouse_id = $s->warehouse_id;
                    $stock->product_id = $s->product_id;
                    $stock->base_product_unit_id = $s->base_product_unit_id;
                    $stock->display_product_unit_id = $s->display_product_unit_id;
                    $stock->is_current = 1;
                    $stock->quantity_in = $rd->base_netto;
                    $stock->quantity_out = 0;
                    $stock->quantity_current = $s->quantity_current + $rd->base_netto;

                    $new->stock()->save($stock);

                    $s->is_current = 0;
                    $s->save();
                }
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
            return Stock::with('warehouse', 'product.productType')->get();
        } else {
            return Stock::with('warehouse', 'product')->whereWarehouseId($warehouseId)->get();
        }
    }

    public function getStockByProduct()
    {
        $result = [];

        $product = Product::with('productType', 'productUnits.unit')->get();

        foreach ($product as $p) {
            $stock = $this->getInStock($p->id);

            if($stock->count() > 0) {
                foreach ($stock as $s) {
                    array_push($result, array(
                        'stock_product_id' => $s->id.'_'.$p->id,
                        'product_type' => $p->productType->name,
                        'product_name' => $p->name,
                        'product' => $p,
                        'in_stock' => 1,
                        'warehouse_name' => $s->warehouse->name,
                        'base_total' => $s->quantity_current,
                        'base_unit' => $s->baseUnit,
                        'display_total' => 0,
                        'display_unit' => $s->displayUnit,
                        'in_stock_date' => $s->stockDate
                    ));
                }
            } else {
                array_push($result, array(
                    'stock_product_id' => '0_'.$p->id,
                    'product_type' => $p->productType->name,
                    'product_name' => $p->name,
                    'product' => $p,
                    'in_stock' => 0,
                    'warehouse_name' => '',
                    'base_total' => 0,
                    'base_unit' => $p->baseUnit,
                    'display_total' => 0,
                    'display_unit' => $p->displayUnit,
                    'in_stock_date' => ''
                ));
            }
        }

        return $result;
    }

    private function getInStock($productId)
    {
        $stock = Stock::with('warehouse', 'owner')->whereProductId($productId)->get();

        return $stock;
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
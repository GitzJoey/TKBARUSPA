<?php
/**
 * Created by PhpStorm.
 * User: TKBARU
 * Date: 4/13/2018
 * Time: 9:37 PM
 */

namespace App\Services\Implementations;

use App\Models\Item;
use App\Models\Expense;
use App\Models\Receipt;
use App\Models\ReceiptDetail;
use App\Models\PurchaseOrder;

use DB;
use Config;
use Exception;
use Carbon\Carbon;

use App\Services\PurchaseOrderService;

class PurchaseOrderServiceImpl implements PurchaseOrderService
{

    public function create(
            $company_id,
            $code,
            $po_type,
            $po_created,
            $shipping_date,
            $supplier_type,
            $items,
            $expenses,
            $supplier_id,
            $walk_in_supplier,
            $walk_in_supplier_detail,
            $warehouse_id,
            $vendor_trucking_id,
            $discount,
            $status,
            $remarks,
            $internal_remarks,
            $private_remarks
    )
    {
        DB::beginTransaction();

        try {
            if ($supplier_type == 'SUPPLIERTYPE.R'){
                $supplier_id = empty($supplier_id) ? 0 : $supplier_id;
                $walk_in_supplier = '';
                $walk_in_supplier_detail = '';
            } else {
                $supplier_id = 0;
            }

            $po = new PurchaseOrder;
            $po->code = $code;
            $po->po_type = $po_type;
            $po->po_created = date('Y-m-d H:i:s', strtotime($po_created));
            $po->shipping_date = date('Y-m-d H:i:s', strtotime($shipping_date));
            $po->supplier_type = $supplier_type;
            $po->walk_in_supplier = $walk_in_supplier;
            $po->walk_in_supplier_detail = $walk_in_supplier_detail;
            $po->remarks = $remarks;
            $po->internal_remarks = $internal_remarks;
            $po->private_remarks = $private_remarks;
            $po->status = 'POSTATUS.WA';
            $po->supplier_id = $supplier_id;
            $po->vendor_trucking_id = $vendor_trucking_id;
            $po->warehouse_id = $warehouse_id;
            $po->company_id = $company_id;
            $po->discount = $discount;

            $po->save();

            for ($i = 0; $i < count($items); $i++) {
                $item = new Item();
                $item->product_id = $items[$i]["product_id"];
                $item->company_id = $items[$i]["company_id"];
                $item->selected_product_unit_id = $items[$i]["selected_product_unit_id"];
                $item->base_product_unit_id = $items[$i]["base_product_unit_id"];
                $item->conversion_value = $items[$i]["conversion_value"];
                $item->quantity = $items[$i]["quantity"];
                $item->discount = $items[$i]["discount"];
                $item->price = $items[$i]["price"];
                $item->to_base_quantity = $item->quantity * $item->conversion_value;

                $po->items()->save($item);
            }

            for($i = 0; $i < count($expenses); $i++){
                $expense = new Expense();
                $expense->name = $expenses[$i]["name"];
                $expense->type = $expenses[$i]["type"];
                $expense->is_internal_expense = !empty($expenses[$i]["is_internal_expense"]);
                $expense->amount = $expenses[$i]["amount"];
                $expense->remarks = $expenses[$i]["remarks"];

                $po->expenses()->save($expense);
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function read()
    {
        return PurchaseOrder::get();
    }

    public function update(
        $id,
        $company_id,
        $code,
        $po_type,
        $po_created,
        $shipping_date,
        $supplier_type,
        $items,
        $inputtedItemIds,
        $expenses,
        $inputtedExpenseIds,
        $supplier_id,
        $walk_in_supplier,
        $walk_in_supplier_detail,
        $warehouse_id,
        $vendor_trucking_id,
        $discount,
        $status,
        $remarks,
        $internal_remarks,
        $private_remarks
    )
    {
        DB::beginTransaction();

        try {
            // Get current PO
            $currentPo = PurchaseOrder::with('items', 'expenses')->find($id);

            // Get IDs of current PO's items
            $poItemsId = $currentPo->items->map(function ($item) {
                return $item->id;
            })->all();

            // Get the id of removed items
            $poItemsToBeDeleted = array_diff($poItemsId, $inputtedItemIds);

            // Remove the items that removed on the revise page
            Item::destroy($poItemsToBeDeleted);

            $currentPo->shipping_date = date('Y-m-d H:i:s', strtotime($shipping_date));
            $currentPo->warehouse_id = $warehouse_id;
            $currentPo->vendor_trucking_id = empty($vendor_trucking_id) ? 0 : $vendor_trucking_id;
            $currentPo->remarks = $remarks;
            $currentPo->internal_remarks = $internal_remarks;
            $currentPo->private_remarks = $private_remarks;
            $currentPo->discount = $discount;

            for ($i = 0; $i < count($inputtedItemIds); $i++) {
                $item = Item::findOrNew($inputtedItemIds[$i]);
                $item->product_id = $items[$i]['product_id'];
                $item->company_id = $items[$i]['company_id'];
                $item->selected_product_unit_id = $items[$i]['selected_product_unit_id'];
                $item->base_product_unit_id = $items[$i]['base_product_unit_id'];
                $item->conversion_value = $items[$i]['conversion_value'];
                $item->quantity = $items[$i]['quantity'];
                $item->price = $items[$i]['price'];
                $item->discount = $items[$i]['discount'];
                $item->to_base_quantity = $item->quantity * $item->conversion_value;

                $currentPo->items()->save($item);
            }

            // Get IDs of current PO's expenses
            $poExpensesId = $currentPo->expenses->map(function ($expense) {
                return $expense->id;
            })->all();

            // Get the id of removed expenses
            $poExpensesToBeDeleted = array_diff($poExpensesId, $inputtedExpenseIds);

            // Remove the expenses that removed on the revise page
            Expense::destroy($poExpensesToBeDeleted);

            for($i = 0; $i < count($inputtedExpenseIds); $i++){
                $expense = Expense::findOrNew($inputtedExpenseIds[$i]);
                $expense->name = $expenses[$i]['name'];
                $expense->type = $expenses[$i]['type'];
                $expense->is_internal_expense = $expenses[$i]['is_internal_expense'];
                $expense->amount = $expenses[$i]['amount'];
                $expense->remarks = $expenses[$i]['remarks'];

                $currentPo->expenses()->save($expense);
            }

            $currentPo->save();

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function addReceipt($poId, $receipt, $receiptDetailArr)
    {
        $currentPO = PurchaseOrder::findOrFail($poId);

        $r = new Receipt();
        $r->company_id = $receipt['company_id'];
        $r->vendor_trucking_id = $receipt['vendor_trucking_id'];
        $r->truck_id = $receipt['truck_id'];
        $r->article_code = $receipt['article_code'];
        $r->driver_name = $receipt['driver_name'];
        $r->receipt_date = $receipt['receipt_date'];
        $r->status = Config::get('lookup.VALUE.RECEIPT_STATUS.NEW');
        $r->remarks = $receipt['remarks'];

        $currentPO->receipts()->save($r);

        for ($i = 0; $i < count($receiptDetailArr); $i++) {
            $rd = new ReceiptDetail();
            $rd->company_id = $receiptDetailArr[$i]['company_id'];
            $rd->item_id = $receiptDetailArr[$i]['item_id'];
            $rd->selected_product_unit_id = $receiptDetailArr[$i]['selected_product_unit_id'];
            $rd->base_product_unit_id = $receiptDetailArr[$i]['base_product_unit_id'];
            $rd->conversion_value = $receiptDetailArr[$i]['conversion_value'];
            $rd->brutto = $receiptDetailArr[$i]['brutto'];
            $rd->base_brutto = $receiptDetailArr[$i]['conversion_value'] * $receiptDetailArr[$i]['brutto'];
            $rd->netto = $receiptDetailArr[$i]['netto'];
            $rd->base_netto = $receiptDetailArr[$i]['conversion_value'] * $receiptDetailArr[$i]['netto'];
            $rd->tare = $receiptDetailArr[$i]['tare'];
            $rd->base_tare = $receiptDetailArr[$i]['conversion_value'] * $receiptDetailArr[$i]['tare'];

            $r->receiptDetails()->save($rd);
        }

        return $r;
    }

    public function addExpenses($poId, $expensesArr)
    {
        $currentPO = PurchaseOrder::findOrFail($poId);

        for($i = 0; $i < count($expensesArr); $i++){
            $expense = new Expense();
            $expense->name = $expensesArr[$i]['name'];
            $expense->type = $expensesArr[$i]['type'];
            $expense->is_internal_expense = $expensesArr[$i]['is_internal_expense'];
            $expense->amount = $expensesArr[$i]['amount'];
            $expense->remarks = $expensesArr[$i]['remarks'];

            $currentPO->expenses()->save($expense);
        }
    }

    public function generatePOCode()
    {
        $result = '';

        do
        {
            $length = Config::get('const.TRXCODE.LENGTH');
            $generatedString = '';
            $characters = array_merge(Config::get('const.RANDOMSTRINGRANGE.ALPHABET'), Config::get('const.RANDOMSTRINGRANGE.NUMERIC'));
            $max = sizeof($characters) - 1;

            for ($i = 0; $i < $length; $i++) {
                $generatedString .= $characters[mt_rand(0, $max)];
            }

            $temp_result = strtoupper($generatedString);

            $po = PurchaseOrder::whereCode($temp_result);
            if (empty($po->first())) {
                $result = $temp_result;
            }
        } while (empty($result));

        return $result;
    }

    public function getPODates($limit = 50)
    {
        $po = PurchaseOrder::all()->groupBy(function ($po) {
            return $po->po_created->format('Y-m-d');
        })->take($limit)->map(function($item) {
            return $item->all()[0]->po_created->format('Y-m-d');
        });

        $poResult = [];
        foreach ($po as $p) {
            if (!in_array($p, $poResult)) {
                array_push($poResult, $p);
            }
        }

        return $poResult;
    }

    public function getPOById($id)
    {
        $purchaseOrder = PurchaseOrder::with([
            'items.product.productUnits.unit'
            ,'items.selectedProductUnit.unit'
            ,'items.baseProductUnit.unit'
            ,'supplier.personsInCharge'
            ,'supplier.products.productType'
            ,'expenses'
            ,'warehouse'
            ,'vendorTrucking'
            //,'receipts.item.product'
            //,'receipts.item.selectedUnit' => function($q) { $q->with('unit')->withTrashed(); }
        ])->where('id', '=', $id)->firstOrFail();

        return $purchaseOrder;
    }

    public function searchPOByDate($date)
    {
        $date = Carbon::parse($date)->format(Config::get('const.DATETIME_FORMAT.DATABASE_DATE'));

        $purchaseOrders = PurchaseOrder::with([
            'items.product.productUnits.unit'
            ,'items.selectedProductUnit.unit'
            ,'items.baseProductUnit.unit'
            ,'supplier.personsInCharge'
            ,'supplier.products.productType'
            ,'expenses'
            ,'warehouse'
            ,'vendorTrucking'
            ,'receipts.receiptDetails'
        ])->where('po_created', 'like', $date.'%')->get();

        return $purchaseOrders;
    }

    public function searchPOByStatus($status)
    {
        $purchaseOrders = PurchaseOrder::with([
            'items.product.productUnits.unit'
            ,'items.selectedProductUnit.unit'
            ,'items.baseProductUnit.unit'
            ,'supplier.personsInCharge'
            ,'supplier.products.productType'
            ,'expenses'
            ,'warehouse'
            ,'vendorTrucking'
            ,'receipts.receiptDetails'
        ])->where('status', '=', $status)->get();

        return $purchaseOrders;
    }

    public function getAllWaitingArrivalPO($warehouseId, $status)
    {
        $purchaseOrders = PurchaseOrder::with([
            'items.product.productUnits.unit'
            ,'items.selectedProductUnit.unit'
            ,'items.baseProductUnit.unit'
            ,'supplier.personsInCharge'
            ,'supplier.products.productType'
            ,'expenses'
            ,'warehouse'
            ,'vendorTrucking'
            ,'receipts.receiptDetails'
        ])->where('status', '=', $status)->where('warehouse_id', '=', $warehouseId)->get();

        return $purchaseOrders;
    }
}
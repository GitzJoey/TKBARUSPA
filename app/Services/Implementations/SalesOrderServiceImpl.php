<?php
/**
 * Created by PhpStorm.
 * User: gitzj
 * Date: 5/31/2018
 * Time: 11:59 PM
 */

namespace App\Services\Implementations;

use App\Models\Item;
use App\Models\Expense;
use App\Models\Deliver;
use App\Models\DeliverDetail;
use App\Models\SalesOrder;

use Config;
use Carbon\Carbon;

use App\Services\SalesOrderService;

class SalesOrderServiceImpl implements SalesOrderService
{
    public function create(
        $company_id,
        $code,
        $so_type,
        $so_created,
        $shipping_date,
        $customer_type,
        $items,
        $expenses,
        $customer_id,
        $walk_in_customer,
        $walk_in_customer_detail,
        $vendor_trucking_id,
        $discount,
        $status,
        $remarks,
        $internal_remarks,
        $private_remarks
    )
    {
        if ($customer_type == 'CUSTOMERTYPE.R'){
            $customer_id = empty($customer_id) ? 0 : $customer_id;
            $walk_in_cust = '';
            $walk_in_cust_detail = '';
        } else {
            $customer_id = 0;
        }

        $so = new SalesOrder;
        $so->code = $code;
        $so->so_type = $so_type;
        $so->so_created = date('Y-m-d H:i:s', strtotime($so_created));
        $so->shipping_date = date('Y-m-d H:i:s', strtotime($shipping_date));
        $so->customer_type = $customer_type;
        $so->walk_in_cust = $walk_in_cust;
        $so->walk_in_cust_detail = $walk_in_cust_detail;
        $so->remarks = $remarks;
        $so->internal_remarks = $internal_remarks;
        $so->private_remarks = $private_remarks;
        $so->status = 'SOSTATUS.WD';
        $so->customer_id = $customer_id;
        $so->vendor_trucking_id = $vendor_trucking_id;
        $so->company_id = $company_id;
        $so->discount = $discount;

        $so->save();

        for ($i = 0; $i < count($items); $i++) {
            $item = new Item();
            $item->product_id = $items[$i]["product_id"];
            $item->stock_id = $items[$i]["stock_id"];
            $item->company_id = $items[$i]["company_id"];
            $item->selected_product_unit_id = $items[$i]["selected_product_unit_id"];
            $item->base_product_unit_id = $items[$i]["base_product_unit_id"];
            $item->conversion_value = $items[$i]["conversion_value"];
            $item->quantity = $items[$i]["quantity"];
            $item->discount = $items[$i]["discount"];
            $item->price = $items[$i]["price"];
            $item->to_base_quantity = $item->quantity * $item->conversion_value;

            $so->items()->save($item);
        }

        for($i = 0; $i < count($expenses); $i++){
            $expense = new Expense();
            $expense->name = $expenses[$i]["name"];
            $expense->type = $expenses[$i]["type"];
            $expense->is_internal_expense = !empty($expenses[$i]["is_internal_expense"]);
            $expense->amount = $expenses[$i]["amount"];
            $expense->remarks = $expenses[$i]["remarks"];

            $so->expenses()->save($expense);
        }
    }

    public function read()
    {
        return SalesOrder::get();
    }

    public function update(
        $id,
        $company_id,
        $code,
        $so_type,
        $so_created,
        $shipping_date,
        $customer_type,
        $items,
        $inputtedItemIds,
        $expenses,
        $inputtedExpenseIds,
        $customer_id,
        $walk_in_cust,
        $walk_in_cust_detail,
        $vendor_trucking_id,
        $discount,
        $status,
        $remarks,
        $internal_remarks,
        $private_remarks
    )
    {
        // Get current SO
        $currentSo = SalesOrder::with('items', 'expenses')->find($id);

        // Get IDs of current SO's items
        $soItemsId = $currentSo->items->map(function ($item) {
            return $item->id;
        })->all();

        // Get the id of removed items
        $soItemsToBeDeleted = array_diff($soItemsId, $inputtedItemIds);

        // Remove the items that removed on the revise page
        Item::destroy($soItemsToBeDeleted);

        $currentSo->shipping_date = date('Y-m-d H:i:s', strtotime($shipping_date));
        $currentSo->vendor_trucking_id = empty($vendor_trucking_id) ? 0 : $vendor_trucking_id;
        $currentSo->remarks = $remarks;
        $currentSo->internal_remarks = $internal_remarks;
        $currentSo->private_remarks = $private_remarks;
        $currentSo->discount = $discount;

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

            $currentSo->items()->save($item);
        }

        // Get IDs of current SO's expenses
        $soExpensesId = $currentSo->expenses->map(function ($expense) {
            return $expense->id;
        })->all();

        // Get the id of removed expenses
        $soExpensesToBeDeleted = array_diff($soExpensesId, $inputtedExpenseIds);

        // Remove the expenses that removed on the revise page
        Expense::destroy($soExpensesToBeDeleted);

        for($i = 0; $i < count($inputtedExpenseIds); $i++){
            $expense = Expense::findOrNew($inputtedExpenseIds[$i]);
            $expense->name = $expenses[$i]['name'];
            $expense->type = $expenses[$i]['type'];
            $expense->is_internal_expense = $expenses[$i]['is_internal_expense'];
            $expense->amount = $expenses[$i]['amount'];
            $expense->remarks = $expenses[$i]['remarks'];

            $currentSo->expenses()->save($expense);
        }

        $currentSo->save();
    }

    public function addDeliver($soId, $deliver, $deliverDetailArr)
    {
        $currentSO = SalesOrder::findOrFail($soId);

        $d = new Deliver();
        $d->company_id = $deliver['company_id'];
        $d->vendor_trucking_id = $deliver['vendor_trucking_id'];
        $d->truck_id = $deliver['truck_id'];
        $d->article_code = $deliver['article_code'];
        $d->driver_name = $deliver['driver_name'];
        $d->deliver_date = $deliver['deliver_date'];
        $d->status = Config::get('lookup.VALUE.DELIVER_STATUS.NEW');
        $d->remarks = $deliver['remarks'];

        $currentSO->delivers()->save($d);

        for ($i = 0; $i < count($deliverDetailArr); $i++) {
            $dd = new DeliverDetail();
            $dd->company_id = $deliverDetailArr[$i]['company_id'];
            $dd->item_id = $deliverDetailArr[$i]['item_id'];
            $dd->selected_product_unit_id = $deliverDetailArr[$i]['selected_product_unit_id'];
            $dd->base_product_unit_id = $deliverDetailArr[$i]['base_product_unit_id'];
            $dd->conversion_value = $deliverDetailArr[$i]['conversion_value'];
            $dd->brutto = $deliverDetailArr[$i]['brutto'];
            $dd->base_brutto = $deliverDetailArr[$i]['conversion_value'] * $deliverDetailArr[$i]['brutto'];
            $dd->netto = $deliverDetailArr[$i]['netto'];
            $dd->base_netto = $deliverDetailArr[$i]['conversion_value'] * $deliverDetailArr[$i]['netto'];
            $dd->tare = $deliverDetailArr[$i]['tare'];
            $dd->base_tare = $deliverDetailArr[$i]['conversion_value'] * $deliverDetailArr[$i]['tare'];

            $d->deliverDetails()->save($dd);
        }

        return $d;
    }

    public function addExpenses($soId, $expensesArr)
    {
        $currentSO = SalesOrder::findOrFail($soId);

        for($i = 0; $i < count($expensesArr); $i++){
            $expense = new Expense();
            $expense->name = $expensesArr[$i]['name'];
            $expense->type = $expensesArr[$i]['type'];
            $expense->is_internal_expense = $expensesArr[$i]['is_internal_expense'];
            $expense->amount = $expensesArr[$i]['amount'];
            $expense->remarks = $expensesArr[$i]['remarks'];

            $currentSO->expenses()->save($expense);
        }
    }

    public function generateSOCode()
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

            $so = SalesOrder::whereCode($temp_result);
            if (empty($so->first())) {
                $result = $temp_result;
            }
        } while (empty($result));

        return $result;
    }

    public function getSOById($id)
    {

    }

    public function searchSOByDate($date)
    {
        $date = Carbon::parse($date)->format(Config::get('const.DATETIME_FORMAT.DATABASE_DATE'));

        $salesOrders = SalesOrder::with([
            'items.product.productUnits.unit'
            ,'items.selectedProductUnit.unit'
            ,'items.baseProductUnit.unit'
            ,'customer.personsInCharge'
            ,'expenses'
            ,'vendorTrucking'
            ,'delivers.deliverDetails'
        ])->where('so_created', 'like', $date.'%')->get();

        return $salesOrders;
    }

    public function searchSOByStatus($status)
    {
        $salesOrders = SalesOrder::with([
            'items.product.productUnits.unit'
            ,'items.selectedProductUnit.unit'
            ,'items.baseProductUnit.unit'
            ,'supplier.personsInCharge'
            ,'supplier.products.productType'
            ,'expenses'
            ,'vendorTrucking'
            ,'delivers.deliverDetails'
        ])->where('status', '=', $status)->get();

        return $salesOrders;
    }

    public function getAllWaitingDeliverSO($warehouseId, $status)
    {
        $salesOrders = SalesOrder::with([
            'items.stock'
            ,'items.product.productUnits.unit'
            ,'items.selectedProductUnit.unit'
            ,'items.baseProductUnit.unit'
            ,'customer.personsInCharge'
            ,'expenses'
            ,'vendorTrucking'
            ,'delivers.deliverDetails.item.product'
        ])->whereHas('items.stock', function($s) use ($warehouseId) {
            $s->where('warehouse_id', '=', $warehouseId);
        })->where('status', '=', $status)->get();


        return $salesOrders;
    }
}
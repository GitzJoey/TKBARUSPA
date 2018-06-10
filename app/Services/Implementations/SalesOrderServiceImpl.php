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
        $so->walk_in_supplier = $walk_in_cust;
        $so->walk_in_supplier_detail = $walk_in_cust_detail;
        $so->remarks = $remarks;
        $so->internal_remarks = $internal_remarks;
        $so->private_remarks = $private_remarks;
        $so->status = 'SOSTATUS.WA';
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

    }

    public function addDeliver($soId, $deliver, $deliverDetailArr)
    {

    }

    public function addExpenses($soId, $expensesArr)
    {

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
            ,'warehouse'
            ,'vendorTrucking'
            ,'delivers.deliverDetails'
        ])->where('so_created', 'like', $date.'%')->get();

        return $salesOrders;
    }

    public function searchSOByStatus($status)
    {

    }

    public function getAllWaitingDeliverSO($warehouseId, $status)
    {

    }
}
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
        $warehouse_id,
        $vendor_trucking_id,
        $discount,
        $status,
        $remarks,
        $internal_remarks,
        $private_remarks
    )
    {

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
        $warehouse_id,
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
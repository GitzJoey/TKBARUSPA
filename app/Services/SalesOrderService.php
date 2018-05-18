<?php
/**
 * Created by PhpStorm.
 * User: gitzj
 * Date: 5/17/2018
 * Time: 1:34 PM
 */

namespace App\Services;

interface SalesOrderService
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
    );
    public function read();
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
        $discount,
        $status,
        $remarks,
        $internal_remarks,
        $private_remarks
    );
}
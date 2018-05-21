<?php
/**
 * Created by PhpStorm.
 * User: gitzj
 * Date: 3/29/2018
 * Time: 9:29 PM
 */

namespace App\Services;

interface PurchaseOrderService
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
    );
    public function read();
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
    );

    public function addReceipt($poId, $receipt, $receiptDetailArr);

    public function addExpenses($poId, $expensesArr);

    public function generatePOCode();

    public function getPODates($limit);

    public function getPOById($id);

    public function searchPOByDate($date);

    public function searchPOByStatus($status);

    public function getAllWaitingArrivalPO($warehouseId, $status);
}
<?php

namespace App\Services;

interface WarehouseInflowService
{
    public function createReceipt(
        $company_id,
        $po_id,
        $receipts,
        $expenses
    );
    public function readReceipt();
    public function updateReceipt(
        $id,
        $company_id,
        $name,
        $address,
        $phone_num,
        $status,
        $remarks,
        $sections
    );
    public function deleteReceipt($id);
}

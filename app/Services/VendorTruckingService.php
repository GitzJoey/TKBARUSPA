<?php

namespace App\Services;

interface VendorTruckingService
{
    public function create(
        $company_id,
        $name,
        $address,
        $tax_id,
        $status,
        $remarks,
        $bankAccounts,
        $trucks
    );

    public function read();

    public function readAllTrucksMaintainedByCompany();

    public function update(
        $id,
        $company_id,
        $name,
        $address,
        $tax_id,
        $status,
        $remarks,
        $maintenanceByCompany,
        $bankAccounts,
        $inputtedBankAccountIds,
        $trucks,
        $inputtedTruckIds
    );

    public function delete($id);
}

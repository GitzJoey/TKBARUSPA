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
        $bankAccounts
    );

    public function read();

    public function update(
        $id,
        $company_id,
        $name,
        $address,
        $tax_id,
        $status,
        $remarks,
        $bankAccounts
    );

    public function delete($id);
}

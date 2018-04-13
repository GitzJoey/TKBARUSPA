<?php

namespace App\Services;

interface WarehouseService
{
    public function create(
        $company_id,
        $name,
        $address,
        $phone_num,
        $status,
        $remarks,
        $sections
    );
    public function read();
    public function update(
        $id,
        $company_id,
        $name,
        $address,
        $phone_num,
        $status,
        $remarks,
        $sections
    );
    public function delete($id);
}

<?php
/**
 * Created by PhpStorm.
 * User: gitzj
 * Date: 3/29/2018
 * Time: 9:35 PM
 */

namespace App\Services\Implementations;

use App\Models\Supplier;

use App\Services\SupplierService;

class SupplierServiceImpl implements SupplierService
{
    public function create(
        $company_id,
        $name,
        $address,
        $city,
        $phone_number,
        $fax_num,
        $tax_id,
        $status,
        $remarks,
        $payment_due_day,
        $bank_accounts,
        $persons_in_charge,
        $product_selected
    )
    {
        // TODO: Implement create() method.
    }

    public function read()
    {
        // TODO: Implement read() method.
    }

    public function update(
        $id,
        $company_id,
        $name,
        $address,
        $city,
        $phone_number,
        $fax_num,
        $tax_id,
        $status,
        $remarks,
        $payment_due_day,
        $bank_accounts,
        $persons_in_charge,
        $product_selected
    )
    {
        // TODO: Implement update() method.
    }

    public function delete($id)
    {
        // TODO: Implement delete() method.
    }
}
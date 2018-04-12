<?php
/**
 * Created by PhpStorm.
 * User: gitzj
 * Date: 3/29/2018
 * Time: 9:29 PM
 */

namespace App\Services;

interface SupplierService
{
    public function create(
        $company_id,
        $name,
        $code_sign,
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
    );
    public function read();
    public function update(
        $id,
        $company_id,
        $name,
        $code_sign,
        $address,
        $city,
        $phone_number,
        $fax_num,
        $tax_id,
        $status,
        $remarks,
        $payment_due_day,
        $bank_accounts,
        $inputtedBankAccountId,
        $persons_in_charge,
        $inputtedProfileId,
        $inputtedPhoneNumberId,
        $product_selected
    );
    public function delete($id);
}
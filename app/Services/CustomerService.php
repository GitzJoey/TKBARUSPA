<?php
namespace App\Services;

use App\Model\Customer;

interface CustomerService 
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
        $persons_in_charge
	);
	public function read();
    public function readAll();
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
        $persons_in_charge
	);
	public function delete(
		$id
	);
}
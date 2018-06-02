<?php
namespace App\Services;

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
        $price_level,
        $payment_due_day,
        $bank_accounts,
        $persons_in_charge
	);

	public function read($searchQuery = '');

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
        $price_level,
        $payment_due_day,
        $bank_accounts,
        $inputtedBankAccountId,
        $persons_in_charge,
        $inputtedProfileId,
        $inputtedPhoneNumberId
	);

	public function delete(
		$id
	);

	public function searchCustomer($query);
}
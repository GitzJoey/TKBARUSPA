<?php
/**
 * Created by PhpStorm.
 * User: gitzj
 * Date: 3/29/2018
 * Time: 9:35 PM
 */

namespace App\Services\Implementations;

use App\Models\Product;
use App\Models\Profile;
use App\Models\Supplier;
use App\Models\BankAccount;
use App\Models\PhoneNumber;

use DB;
use Config;
use Exception;
use LaravelLocalization;
use App\Services\SupplierService;

class SupplierServiceImpl implements SupplierService
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
    )
    {
        DB::beginTransaction();
        try {
            $suppliers = [
                'company_id' => $company_id,
                'name' => $name,
                'address' => $address,
                'city' => $city,
                'phone_number' => $phone_number,
                'fax_num' => $fax_num,
                'tax_id' => $tax_id,
                'status' => $status,
                'remarks' => $remarks,
                'payment_due_day' => $payment_due_day,
            ];

            $supplier = Supplier::create($suppliers);

            for ($i = 0; $i < count($bank_accounts); $i++) {
                $ba = new BankAccount();
                $ba->bank_id = $bank_accounts[$i]["bank_id"];
                $ba->account_name = $bank_accounts[$i]["account_name"];
                $ba->account_number = $bank_accounts[$i]["account_number"];
                $ba->remarks = $bank_accounts[$i]["bank_remarks"];

                $supplier->bankAccounts()->save($ba);
            }

            for ($i = 0; $i < count($persons_in_charge); $i++) {
                $pa = new Profile();
                $pa->first_name = $persons_in_charge[$i]['first_name'];
                $pa->last_name = $persons_in_charge[$i]['last_name'];
                $pa->address = $persons_in_charge[$i]['address'];
                $pa->ic_num = $persons_in_charge[$i]['ic_num'];

                $supplier->personsInCharge()->save($pa);

                for ($j = 0; $j < count($persons_in_charge[$i]['phone_numbers']); $j++) {
                    $ph = new PhoneNumber();
                    $ph->phone_provider_id = $persons_in_charge[$i]['phone_numbers'][$j]['phone_provider_id'];
                    $ph->number = $persons_in_charge[$i]['phone_numbers'][$j]['number'];
                    $ph->remarks = $persons_in_charge[$i]['phone_numbers'][$j]['remarks'];

                    $pa->phoneNumbers()->save($ph);
                }
            }

            for ($i = 0; $i < count($product_selected); $i++) {
                $pr = Product::whereId($product_selected[$i])->first();
                $supplier->products()->save($pr);
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        };
    }

    public function read()
    {
        return Supplier::with('personsInCharge.phoneNumbers.provider', 'bankAccounts.bank', 'products')->paginate(Config::get('const.PAGINATION'));
    }

    public function readAll()
    {
        return Supplier::with('personsInCharge.phoneNumbers.provider', 'bankAccounts.bank', 'products')
            ->get();
    }

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
    )
    {
        DB::beginTransaction();
        try {
            $supplier = Supplier::with('bankAccounts', 'personsInCharge.phoneNumbers.provider', 'products')->findOrFail($id);

            if (!$supplier) {
                throw new Exception(LaravelLocalization::getCurrentLocale() == 'en' ? 'Supplier Not Found.':'Supplier Tidak Ditermukan.');
            }

            $supplierBankAccountIds = $supplier->bankAccounts->map(function ($bankAccount) {
                return $bankAccount->id;
            })->all();

            $supplierBankAccountsToBeDeleted = array_diff($supplierBankAccountIds, isset($inputtedBankAccountId) ?
                $inputtedBankAccountId : []);

            BankAccount::destroy($supplierBankAccountsToBeDeleted);

            for ($i = 0; $i < count($bank_accounts); $i++) {
                $ba = BankAccount::findOrNew($bank_accounts[$i]['bank_account_id']);
                $ba->bank_id = $bank_accounts[$i]['bank_id'];
                $ba->account_name= $bank_accounts[$i]['account_name'];
                $ba->account_number = $bank_accounts[$i]['account_number'];
                $ba->remarks = $bank_accounts[$i]['bank_remarks'];

                $supplier->bankAccounts()->save($ba);
            }

            $supplierProfileIds = $supplier->personsInCharge->map(function ($profile) {
                return $profile->id;
            })->all();

            $supplierProfilesToBeDeleted = array_diff($supplierProfileIds, isset($inputtedProfileId) ?
                $inputtedProfileId : []);

            Profile::destroy($supplierProfilesToBeDeleted);

            for ($i = 0; $i < count($persons_in_charge); $i++) {
                $pa = Profile::with('phoneNumbers')->findOrNew($persons_in_charge[$i]['profile_id']);
                $pa->first_name = $persons_in_charge[$i]["first_name"];
                $pa->last_name = $persons_in_charge[$i]["last_name"];
                $pa->address = $persons_in_charge[$i]["address"];
                $pa->ic_num = $persons_in_charge[$i]["ic_num"];

                $supplier->personsInCharge()->save($pa);

                $profilePhoneNumberIds = $pa->phoneNumbers->map(function ($phoneNumber) {
                    return $phoneNumber->id;
                })->all();

                $profilePhoneNumbersToBeDeleted = array_diff($profilePhoneNumberIds,
                    isset($inputtedPhoneNumberId) ? $inputtedPhoneNumberId : []);

                PhoneNumber::destroy($profilePhoneNumbersToBeDeleted);

                for ($j = 0; $j < count($persons_in_charge[$i]['phone_numbers']); $j++) {
                    $ph = PhoneNumber::findOrNew($persons_in_charge[$i]['phone_numbers'][$j]['phone_number_id']);
                    $ph->phone_provider_id = $persons_in_charge[$i]['phone_numbers'][$j]['phone_provider_id'];
                    $ph->number = $persons_in_charge[$i]['phone_numbers'][$j]['number'];
                    $ph->remarks = $persons_in_charge[$i]['phone_numbers'][$j]['remarks'];

                    $pa->phoneNumbers()->save($ph);
                }
            }

            $supplier->products()->detach();

            for ($i = 0; $i < count($product_selected); $i++) {
                $pr = Product::whereId($product_selected[$i])->first();
                $supplier->products()->save($pr);
            }

            $supplier->name = $name;
            $supplier->address = $address;
            $supplier->city = $city;
            $supplier->phone_number = $phone_number;
            $supplier->fax_num = $fax_num;
            $supplier->tax_id = $tax_id;
            $supplier->status = $status;
            $supplier->remarks = $remarks;
            $supplier->payment_due_day = $payment_due_day;

            $supplier->save();

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        };
    }

    public function delete($id)
    {
        $supplier = Supplier::findOrFail($id);

        foreach ($supplier->personsInCharge as $p) {
            foreach ($p->phoneNumbers as $ph) {
                $ph->delete();
            }
            $p->delete();
        }

        foreach ($supplier->bankAccounts as $ba) {
            $ba->delete();
        }

        $supplier->products()->detach();

        $supplier->delete();
    }
}
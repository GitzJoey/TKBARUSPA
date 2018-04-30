<?php 
namespace App\Services\Implementations;

use App\Models\Customer;
use App\Models\Profile;
use App\Models\BankAccount;
use App\Models\PhoneNumber;

use DB;
use Config;
use Exception;
use LaravelLocalization;
use App\Services\CustomerService;

class CustomerServiceImpl implements CustomerService
{
	public function read()
    {
        return Customer::with('personsInCharge.phoneNumbers.provider', 'bankAccounts.bank')->paginate(Config::get('const.PAGINATION'));
    }

    public function readAll()
    {
        return Customer::get();
    }

	public function create(
        $company_id,
        $name,
        $sign_code,
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
    )
    {
        DB::beginTransaction();
        try {
            $customers = [
                'company_id' => $company_id,
                'name' => $name,
                'sign_code' => $sign_code,
                'address' => $address,
                'city' => $city,
                'phone_number' => $phone_number,
                'fax_num' => $fax_num,
                'tax_id' => $tax_id,
                'status' => $status,
                'remarks' => $remarks,
                'payment_due_day' => $payment_due_day,
            ];

            $customer = Customer::create($customers);

            for ($i = 0; $i < count($bank_accounts); $i++) {
                $ba = new BankAccount();
                $ba->bank_id = $bank_accounts[$i]["bank_id"];
                $ba->account_name = $bank_accounts[$i]["account_name"];
                $ba->account_number = $bank_accounts[$i]["account_number"];
                $ba->remarks = $bank_accounts[$i]["bank_remarks"];

                $customer->bankAccounts()->save($ba);
            }

            for ($i = 0; $i < count($persons_in_charge); $i++) {
                $pa = new Profile();
                $pa->first_name = $persons_in_charge[$i]['first_name'];
                $pa->last_name = $persons_in_charge[$i]['last_name'];
                $pa->address = $persons_in_charge[$i]['address'];
                $pa->ic_num = $persons_in_charge[$i]['ic_num'];

                $customer->personsInCharge()->save($pa);

                for ($j = 0; $j < count($persons_in_charge[$i]['phone_numbers']); $j++) {
                    $ph = new PhoneNumber();
                    $ph->phone_provider_id = $persons_in_charge[$i]['phone_numbers'][$j]['phone_provider_id'];
                    $ph->number = $persons_in_charge[$i]['phone_numbers'][$j]['number'];
                    $ph->remarks = $persons_in_charge[$i]['phone_numbers'][$j]['remarks'];

                    $pa->phoneNumbers()->save($ph);
                }
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        };
    }

    public function update(
    	$id,
        $company_id,
        $name,
        $sign_code,
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
        $inputtedPhoneNumberId
    )
    {
        DB::beginTransaction();
        try {
            $customer = Customer::with('bankAccounts', 'personsInCharge.phoneNumbers.provider')->findOrFail($id);

            if (!$customer) {
                throw new Exception(LaravelLocalization::getCurrentLocale() == 'en' ? 'Customer Not Found.':'Pelanggan Tidak Ditermukan.');
            }

            $customerBankAccountIds = $customer->bankAccounts->map(function ($bankAccount) {
                return $bankAccount->id;
            })->all();

            $customerBankAccountsToBeDeleted = array_diff($customerBankAccountIds, isset($inputtedBankAccountId) ?
                $inputtedBankAccountId : []);

            BankAccount::destroy($customerBankAccountsToBeDeleted);

            for ($i = 0; $i < count($bank_accounts); $i++) {
                $ba = BankAccount::findOrNew($bank_accounts[$i]['bank_account_id']);
                $ba->bank_id = $bank_accounts[$i]['bank_id'];
                $ba->account_name= $bank_accounts[$i]['account_name'];
                $ba->account_number = $bank_accounts[$i]['account_number'];
                $ba->remarks = $bank_accounts[$i]['bank_remarks'];

                $customer->bankAccounts()->save($ba);
            }

            $customerProfileIds = $customer->personsInCharge->map(function ($profile) {
                return $profile->id;
            })->all();

            $customerProfilesToBeDeleted = array_diff($customerProfileIds, isset($inputtedProfileId) ?
                $inputtedProfileId : []);

            Profile::destroy($customerProfilesToBeDeleted);

            for ($i = 0; $i < count($persons_in_charge); $i++) {
                $pa = Profile::with('phoneNumbers')->findOrNew($persons_in_charge[$i]['profile_id']);
                $pa->first_name = $persons_in_charge[$i]["first_name"];
                $pa->last_name = $persons_in_charge[$i]["last_name"];
                $pa->address = $persons_in_charge[$i]["address"];
                $pa->ic_num = $persons_in_charge[$i]["ic_num"];

                $customer->personsInCharge()->save($pa);

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

            $customer->name = $name;
            $customer->sign_code = $sign_code;
            $customer->address = $address;
            $customer->city = $city;
            $customer->phone_number = $phone_number;
            $customer->fax_num = $fax_num;
            $customer->tax_id = $tax_id;
            $customer->status = $status;
            $customer->remarks = $remarks;
            $customer->payment_due_day = $payment_due_day;

            $customer->save();

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        };
    }

    public function delete($id) 
    {
    	$customer = Customer::findOrFail($id);

        foreach ($customer->personsInCharge as $p) {
            foreach ($p->phoneNumbers as $ph) {
                $ph->delete();
            }
            $p->delete();
        }

        foreach ($customer->bankAccounts as $ba) {
            $ba->delete();
        }

        $customer->delete();
    }

	

}
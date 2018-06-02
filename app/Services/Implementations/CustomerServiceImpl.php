<?php 
namespace App\Services\Implementations;

use App\Models\Customer;
use App\Models\Profile;
use App\Models\BankAccount;
use App\Models\PhoneNumber;

use Config;
use LaravelLocalization;
use App\Services\CustomerService;

class CustomerServiceImpl implements CustomerService
{
	public function read($searchQuery = '')
    {
        $customer = [];
        if (strlen($searchQuery)) {
            $param = $searchQuery;
            $customer = Customer::with('personsInCharge.phoneNumbers.provider', 'bankAccounts.bank', 'priceLevel')
                ->where('name', 'like', '%'.$param.'%')
                ->orWhereHas('personsInCharge', function ($query) use ($param) {
                    $query->where('first_name', 'like', '%'.$param.'%')
                        ->orWhere('last_name', 'like', '%'.$param.'%');
                })->paginate(Config::get('const.PAGINATION'));
        } else {
            $customer = Customer::with('personsInCharge.phoneNumbers.provider', 'bankAccounts.bank', 'priceLevel')
                ->paginate(Config::get('const.PAGINATION'));
        }

        return $customer;
    }

    public function readAll()
    {
        return Customer::with('personsInCharge.phoneNumbers.provider', 'bankAccounts.bank')->get();
    }

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
        $price_level_id,
        $payment_due_day,
        $bank_accounts,
        $persons_in_charge
    )
    {
        $customer = new Customer;

        $customer->company_id = $company_id;
        $customer->name = $name;
        $customer->code_sign = $code_sign;
        $customer->address = $address;
        $customer->city = $city;
        $customer->phone_number = $phone_number;
        $customer->fax_num = $fax_num;
        $customer->tax_id = $tax_id;
        $customer->status = $status;
        $customer->remarks = $remarks;
        $customer->price_level_id = $price_level_id;
        $customer->payment_due_day = $payment_due_day;

        $customer->save();

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
        $price_level_id,
        $payment_due_day,
        $bank_accounts,
        $inputtedBankAccountId,
        $persons_in_charge,
        $inputtedProfileId,
        $inputtedPhoneNumberId
    )
    {
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
        $customer->code_sign = $code_sign;
        $customer->address = $address;
        $customer->city = $city;
        $customer->phone_number = $phone_number;
        $customer->fax_num = $fax_num;
        $customer->tax_id = $tax_id;
        $customer->status = $status;
        $customer->remarks = $remarks;
        $customer->price_level_id = $price_level_id;
        $customer->payment_due_day = $payment_due_day;

        $customer->save();
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

    public function searchCustomer($query)
    {
        $param = $query;
        $customer = Customer::with('personsInCharge.phoneNumbers.provider', 'bankAccounts.bank', 'priceLevel')
            ->where('name', 'like', '%'.$param.'%')
            ->orWhereHas('personsInCharge', function ($query) use ($param) {
                $query->where('first_name', 'like', '%'.$param.'%')
                    ->orWhere('last_name', 'like', '%'.$param.'%');
            })->get();

        return $customer;
    }
}
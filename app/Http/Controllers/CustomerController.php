<?php

namespace App\Http\Controllers;

use DB;
use Auth;
use Exception;
use Validator;
use Illuminate\Http\Request;
use Vinkla\Hashids\Facades\Hashids;

use App\Services\CustomerService;

class CustomerController extends Controller
{
    private $customerService;
    
	public function __construct(CustomerService $customerService)
    {
        $this->middleware('auth');
        $this->customerService = $customerService;
    }

    public function index()
    {
        return view('customer.index');
    }

    public function read(Request $request) 
    {
    	$all = $request->has('all');

        if ($all) {
            return $this->customerService->readAll();
        } else {
            $searchQuery = $request->has('s') ? $request->query('s'):'';
            return $this->customerService->read($searchQuery);
        }
    }

    public function store(Request $request) 
    {
        Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'status' => 'required',
            'payment_due_day' => 'required'
        ])->validate();

        DB::beginTransaction();
        try {
            $bank_accounts = [];
            for ($i = 0; $i < count($request['bank_id']); $i++) {
                array_push($bank_accounts, array (
                    'bank_id' => Hashids::decode($request['bank_id'][$i])[0],
                    'account_name' => $request["account_name"][$i],
                    'account_number' => $request["account_number"][$i],
                    'bank_remarks' => $request["bank_remarks"][$i],
                ));
            }

            $persons_in_charge = [];
            for ($i = 0; $i < count($request['first_name']); $i++) {
                $pic_phone = [];
                for ($j = 0; $j < count($request['profile_' . $i . '_phone_provider']); $j++) {
                    array_push($pic_phone, array(
                        'phone_provider_id' => Hashids::decode($request['profile_' . $i . '_phone_provider'][$j])[0],
                        'number' => $request['profile_' . $i . '_phone_number'][$j],
                        'remarks' => $request['profile_' . $i . '_remarks'][$j]
                    ));
                }

                array_push($persons_in_charge, array (
                    'first_name' => $request['first_name'][$i],
                    'last_name' => $request['last_name'][$i],
                    'address' => $request['profile_address'][$i],
                    'ic_num' => $request['ic_num'][$i],
                    'phone_numbers' => $pic_phone
                ));
            }

            $this->customerService->create(
                Auth::user()->company->id,
                $request['name'],
                $request['code_sign'],
                $request['address'],
                $request['city'],
                $request['phone_number'],
                $request['fax_num'],
                $request['tax_id'],
                $request['status'],
                $request['remarks'],
                Hashids::decode($request['price_level_id'])[0],
                $request['payment_due_day'],
                $bank_accounts,
                $persons_in_charge
            );

            DB::commit();
            return response()->json();
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function update(Request $request, $id) 
    {
        Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'status' => 'required',
            'payment_due_day' => 'required'
        ])->validate();

        DB::beginTransaction();
        try {
            $bank_accounts = [];
            $inputtedBankAccountId = [];
            for ($i = 0; $i < count($request['bank_id']); $i++) {
                array_push($bank_accounts, array (
                    'bank_account_id' => is_null($request['bank_account_id'][$i]) ? '' : Hashids::decode($request['bank_account_id'][$i])[0],
                    'bank_id' => Hashids::decode($request['bank_id'][$i])[0],
                    'account_name' => $request["account_name"][$i],
                    'account_number' => $request["account_number"][$i],
                    'bank_remarks' => $request["bank_remarks"][$i],
                ));
                array_push($inputtedBankAccountId, is_null($request['bank_account_id'][$i]) ? '' : Hashids::decode($request['bank_account_id'][$i])[0]);
            }

            $persons_in_charge = [];
            $inputtedProfileId = [];
            $inputtedPhoneNumberId = [];

            for ($i = 0; $i < count($request['first_name']); $i++) {
                $pic_phone = [];
                for ($j = 0; $j < count($request['profile_' . $i . '_phone_provider']); $j++) {
                    array_push($pic_phone, array(
                        'phone_number_id' => is_null($request['profile_' . $i .'_phone_numbers_id'][$j]) ? '' : Hashids::decode($request['profile_' . $i .'_phone_numbers_id'][$j])[0],
                        'phone_provider_id' => Hashids::decode($request['profile_' . $i . '_phone_provider'][$j])[0],
                        'number' => $request['profile_' . $i . '_phone_number'][$j],
                        'remarks' => $request['profile_' . $i . '_remarks'][$j]
                    ));
                    array_push($inputtedPhoneNumberId, is_null($request['profile_' . $i .'_phone_numbers_id'][$j]) ? '' : Hashids::decode($request['profile_' . $i .'_phone_numbers_id'][$j])[0]);
                }

                array_push($persons_in_charge, array (
                    'profile_id' => is_null($request['profile_id'][$i]) ? '' : Hashids::decode($request['profile_id'][$i])[0],
                    'first_name' => $request['first_name'][$i],
                    'last_name' => $request['last_name'][$i],
                    'address' => $request['profile_address'][$i],
                    'ic_num' => $request['ic_num'][$i],
                    'phone_numbers' => $pic_phone
                ));

                array_push($inputtedProfileId, is_null($request['profile_id'][$i]) ? '' : Hashids::decode($request['profile_id'][$i])[0]);
            }

            $this->customerService->update(
                $id,
                Auth::user()->company->id,
                $request['name'],
                $request['code_sign'],
                $request['address'],
                $request['city'],
                $request['phone_number'],
                $request['fax_num'],
                $request['tax_id'],
                $request['status'],
                $request['remarks'],
                Hashids::decode($request['price_level_id'])[0],
                $request['payment_due_day'],
                $bank_accounts,
                $inputtedBankAccountId,
                $persons_in_charge,
                $inputtedProfileId,
                $inputtedPhoneNumberId
            );

            DB::commit();
            return response()->json();
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function delete($id)
    {
        DB::beginTransaction();
        try {
            $this->customerService->delete($id);

            DB::commit();
            return response()->json();
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}

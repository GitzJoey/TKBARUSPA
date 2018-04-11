<?php

namespace App\Http\Controllers;

use Auth;
use Validator;
use Illuminate\Http\Request;

use App\Services\SupplierService;
use Vinkla\Hashids\Facades\Hashids;

class SupplierController extends Controller
{
    private $supplierService;

    public function __construct(SupplierService $supplierService)
    {
        $this->middleware('auth');
        $this->supplierService = $supplierService;
    }

    public function index()
    {
        return view('supplier.index');
    }

    public function read()
    {
        return $this->supplierService->read();
    }

    public function store(Request $request)
    {
        Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'status' => 'required',
            'payment_due_day' => 'required'
        ])->validate();

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

        $product_selected = explode(',', $request['productSelected']);
        for ($i = 0; $i < count($product_selected); $i++) {
            $product_selected[$i] = Hashids::decode($product_selected[$i])[0];
        }

        $this->supplierService->create(
            Auth::user()->company->id,
            $request['name'],
            $request['address'],
            $request['city'],
            $request['phone'],
            $request['fax_num'],
            $request['tax_id'],
            $request['status'],
            $request['remarks'],
            $request['payment_due_day'],
            $bank_accounts,
            $persons_in_charge,
            $product_selected
        );

        return response()->json();
    }

    public function update($id, Request $request)
    {
        Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'status' => 'required',
            'payment_due_day' => 'required'
        ])->validate();

        $bank_accounts = [];
        for ($i = 0; $i < count($request['bank_id']); $i++) {
            array_push($bank_accounts, array (
                'bank_id' => Hashids::decode($request['bank_id'][$i])[0],
                'account_name' => $request["account_name"][$i],
                'account_number' => $request["account_number"][$i],
                'bank_remarks' => $request["bank_remarks"][$i],
            ));
        }
        $inputtedBankAccountId = '';

        $persons_in_charge = [];
        $inputtedProfileId = '';
        $inputtedPhoneNumberId = '';

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

        $product_selected = explode(',', $request['productSelected']);
        for ($i = 0; $i < count($product_selected); $i++) {
            $product_selected[$i] = Hashids::decode($product_selected[$i])[0];
        }

        $this->supplierService->update(
            $id,
            Auth::user()->company->id,
            $request['name'],
            $request['address'],
            $request['city'],
            $request['phone'],
            $request['fax_num'],
            $request['tax_id'],
            $request['status'],
            $request['remarks'],
            $request['payment_due_day'],
            $bank_accounts,
            $inputtedBankAccountId,
            $persons_in_charge,
            $inputtedProfileId,
            $inputtedPhoneNumberId,
            $product_selected
        );

        return response()->json();
    }

    public function delete($id)
    {
        $this->supplierService->delete($id);

        return response()->json();
    }
}

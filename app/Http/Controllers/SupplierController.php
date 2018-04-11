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
        $this->supplierService->read();
    }

    public function store(Request $request)
    {
        Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'status' => 'required',
            'payment_due_day' => 'required'
        ])->validate();

        $bank_accounts = [];
        for ($i = 0; $i < count($request['bank']); $i++) {
            array_push($bank, array (
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
                'first_name' => $request['account_name'][$i],
                'last_name' => $request['account_number'][$i],
                'address' => $request['profile_address'][$i],
                'ic_num' => $request['ic_num'][$i],
                'email' => $request['email'][$i],
                'phone_numbers' => $pic_phone
            ));
        }

        $product_selected = [];
        for ($i = 0; $i < count($request['productSelected']); $i++) {
            array_push($product_selected, $request['productSelected'][$i]);
        }


        $this->supplierService->create(
            Auth::user()->company->id,
            $request['name'],
            $request['address'],
            $request['city'],
            $request['phone_number'],
            $request['fax_num'],
            $request['tax_id'],
            $request['status'],
            $request['remarks'],
            $request['payment_due_day'],
            $bank_accounts,
            $persons_in_charge,
            $product_selected
        );
    }
}

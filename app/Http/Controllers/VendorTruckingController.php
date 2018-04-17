<?php
/**
 * Created by PhpStorm.
 * User: GitzJoey
 * Date: 9/22/2016
 * Time: 3:29 AM
 */

namespace App\Http\Controllers;

use Auth;
use Validator;
use Illuminate\Http\Request;
use Vinkla\Hashids\Facades\Hashids;

use App\Services\VendorTruckingService;

class VendorTruckingController extends Controller
{
    private $vendorTruckingService;

    public function __construct(VendorTruckingService $vendorTruckingService)
    {
        $this->middleware('auth');
        $this->vendorTruckingService = $vendorTruckingService;
    }

    public function index()
    {
        return view('vendor_trucking.index');
    }

    public function read()
    {
        return $this->vendorTruckingService->read();
    }

    public function store(Request $request)
    {
        Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'tax_id' => 'required|string|max:255',
            'status' => 'required',
        ])->validate();

        $bankAccounts = [];

        for ($i = 0; $i < count($request['bank_id']); $i++) {
            array_push($bankAccounts, array (
                'bank_id' => Hashids::decode($request['bank_id'][$i])[0],
                'account_name' => $request["account_name"][$i],
                'account_number' => $request["account_number"][$i],
                'bank_remarks' => $request["bank_remarks"][$i],
            ));
        }

        $this->vendorTruckingService->create(
            Auth::user()->company->id,
            $request['name'],
            $request['address'],
            $request['tax_id'],
            $request['status'],
            $request['remarks'],
            $bankAccounts
        );

        return response()->json();
    }

    public function update($id, Request $req)
    {
        $validator = $this->validate($req, [
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'tax_id' => 'required|string|max:255',
            'status' => 'required',
        ]);

        $bankAccounts = [];

        for ($i = 0; $i < count($req['bank_id']); $i++) {
            array_push($bankAccounts, array (
                'bank_id' => Hashids::decode($req['bank_id'][$i])[0],
                'account_name' => $req["account_name"][$i],
                'account_number' => $req["account_number"][$i],
                'bank_remarks' => $req["bank_remarks"][$i],
            ));
        }

        $this->vendorTruckingService->update(
            $id,
            Auth::user()->company->id,
            $req['name'],
            $req['address'],
            $req['tax_id'],
            $req['status'],
            $req['remarks'],
            $bankAccounts
        );

        return response()->json();
    }

    public function delete($id)
    {
        $this->vendorTruckingService->delete($id);

        return response()->json();
    }
}

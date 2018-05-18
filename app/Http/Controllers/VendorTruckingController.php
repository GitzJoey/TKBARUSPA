<?php
/**
 * Created by PhpStorm.
 * User: GitzJoey
 * Date: 9/22/2016
 * Time: 3:29 AM
 */

namespace App\Http\Controllers;

use DB;
use Auth;
use Exception;
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

    public function readAllTrucksMaintainedByCompany()
    {
        return $this->vendorTruckingService->readAllTrucksMaintainedByCompany();
    }

    public function store(Request $request)
    {
        Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'tax_id' => 'required|string|max:255',
            'status' => 'required',
        ])->validate();

        DB::beginTransaction();
        try {
            $bankAccounts = [];

            for ($i = 0; $i < count($request['bank_id']); $i++) {
                array_push($bankAccounts, array (
                    'bank_id' => Hashids::decode($request['bank_id'][$i])[0],
                    'account_name' => $request["account_name"][$i],
                    'account_number' => $request["account_number"][$i],
                    'bank_remarks' => $request["bank_remarks"][$i],
                ));
            }

            $trucks = [];

            for ($i = 0; $i < count($request['truck_id']); $i++) {
                array_push($trucks, array (
                    'company_id' => Auth::user()->company->id,
                    'type' => $request['truck_type'][$i],
                    'license_plate' => $request["truck_license_plate"][$i],
                    'inspection_date' => $request["truck_inspection_date"][$i],
                    'driver' => $request["truck_driver"][$i],
                    'remarks' => $request["truck_remarks"][$i],
                ));
            }

            $this->vendorTruckingService->create(
                Auth::user()->company->id,
                $request['name'],
                $request['address'],
                $request['tax_id'],
                $request['status'],
                $request['remarks'],
                $bankAccounts,
                $trucks
            );

            DB::commit();
            return response()->json();
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function update($id, Request $req)
    {
        Validator::make($req->all(), [
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'tax_id' => 'required|string|max:255',
            'status' => 'required',
        ])->validate();

        DB::beginTransaction();
        try {
            $bankAccounts = [];
            $inputtedBankAccountIds = [];

            for ($i = 0; $i < count($req['bank_account_id']); $i++) {
                array_push($bankAccounts, array (
                    'bank_account_id' => is_null($req['bank_account_id'][$i]) ? '':Hashids::decode($req['bank_account_id'][$i])[0],
                    'bank_id' => is_null($req['bank_id'][$i]) ? '':Hashids::decode($req['bank_id'][$i])[0],
                    'account_name' => $req["account_name"][$i],
                    'account_number' => $req["account_number"][$i],
                    'bank_remarks' => $req["bank_remarks"][$i],
                ));
                array_push($inputtedBankAccountIds, is_null($req['bank_account_id'][$i]) ? '':Hashids::decode($req['bank_account_id'][$i])[0]);
            }

            $trucks = [];
            $inputtedTruckIds = [];

            for ($i = 0; $i < count($req['truck_id']); $i++) {
                array_push($trucks, array (
                    'company_id' => Auth::user()->company->id,
                    'truck_id' => is_null($req['truck_id'][$i]) ? '':Hashids::decode($req['truck_id'][$i])[0],
                    'type' => $req['truck_type'][$i],
                    'license_plate' => $req["truck_license_plate"][$i],
                    'inspection_date' => $req["truck_inspection_date"][$i],
                    'driver' => $req["truck_driver"][$i],
                    'remarks' => $req["truck_remarks"][$i],
                ));
                array_push($inputtedTruckIds, is_null($req['truck_id'][$i]) ? '':Hashids::decode($req['truck_id'][$i])[0]);
            }

            $this->vendorTruckingService->update(
                $id,
                Auth::user()->company->id,
                $req['name'],
                $req['address'],
                $req['tax_id'],
                $req['status'],
                $req['maintenance_by_company'] ? 1:0,
                $req['remarks'],
                $bankAccounts,
                $inputtedBankAccountIds,
                $trucks,
                $inputtedTruckIds
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
            $this->vendorTruckingService->delete($id);

            DB::commit();
            return response()->json();
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}

<?php
/**
 * Created by PhpStorm.
 * User: GitzJoey
 * Date: 9/7/2016
 * Time: 12:35 AM
 */

namespace App\Http\Controllers;

use Auth;
use Config;
use Validator;
use Illuminate\Http\Request;

use App\Services\TruckMaintenanceService;

class TruckMaintenanceController extends Controller
{
    private $truckMaintenanceService;

    public function __construct(TruckMaintenanceService $truckMaintenanceService)
    {
        $this->middleware('auth');
        $this->truckMaintenanceService = $truckMaintenanceService;
    }

    public function index()
    {
        return view('truck_maintenance.index');
    }

    public function read()
    {
        return $this->truckMaintenanceService->read();
    }

    public function store(Request $request)
    {
        Validator::make($request->all(), [
            'plate_number' => 'required',
            'maintenance_type' => 'required',
            'cost' => 'required|numeric',
            'odometer' => 'required|numeric',
        ])->validate();
        
        $this->truckMaintenanceService->create(
            Auth::user()->company->id,
            $request['plate_number'],
            date(Config::get('const.DATETIME_FORMAT.DATABASE_DATETIME'), strtotime($request->input('maintenance_date'))),
            $request['maintenance_type'],
            $request['cost'],
            $request['odometer'],
            $request['remarks']
        );
        
        return response()->json();
            
    }

    public function update($id, Request $request)
    {
        Validator::make($request->all(), [
            'maintenance_type' => 'required',
            'cost' => 'required|numeric',
            'odometer' => 'required|numeric',
            'remarks' => 'required',
        ])->validate();
        
        $this->truckMaintenanceService->update(
            $id,
            Auth::user()->company->id,
            $request['plate_number'],
            date(Config::get('const.DATETIME_FORMAT.DATABASE_DATETIME'), strtotime($request->input('maintenance_date'))),
            $request['maintenance_type'],
            $request['cost'],
            $request['odometer'],
            $request['remarks']
        );

        return response()->json();
    }

    public function delete($id)
    {
        $this->truckMaintenanceService->delete($id);
        
        return response()->json();
    }
}
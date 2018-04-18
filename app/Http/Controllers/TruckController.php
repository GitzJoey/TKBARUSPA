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

use App\Services\TruckService;

class TruckController extends Controller
{
    private $truckService;

    public function __construct(TruckService $truckService)
    {
        $this->middleware('auth');
        $this->truckService = $truckService;
    }

    public function index()
    {
        return view('truck.index');
    }

    public function read()
    {
        return $this->truckService->read();
    }

    public function store(Request $request)
    {
        Validator::make($request->all(), [
            'plate_number' => 'required|string|max:255',
            'inspection_date' => 'required|string|max:255',
            'driver' => 'required|string|max:255',
            'status' => 'required',
        ])->validate();
        
        $this->truckService->create(
            Auth::user()->company->id,
            $request['truck_type'],
            $request['plate_number'],
            date(Config::get('const.DATETIME_FORMAT.DATABASE_DATETIME'), strtotime($request->input('inspection_date'))),
            $request['driver'],
            $request['status'],
            $request['remarks']
        );
        
        return response()->json();
            
    }

    public function update($id, Request $request)
    {
        Validator::make($request->all(), [
            'plate_number' => 'required|string|max:255',
            'inspection_date' => 'required|string|max:255',
            'driver' => 'required|string|max:255',
            'status' => 'required',
        ])->validate();
        
        $this->truckService->update(
            $id,
            Auth::user()->company->id,
            $request['truck_type'],
            $request['plate_number'],
            date(Config::get('const.DATETIME_FORMAT.DATABASE_DATETIME'), strtotime($request->input('inspection_date'))),
            $request['driver'],
            $request['status'],
            $request['remarks']
        );

        return response()->json();
    }

    public function delete($id)
    {
        $this->truckService->delete($id);
        
        return response()->json();
    }
}
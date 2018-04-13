<?php
/**
 * Created by PhpStorm.
 * User: Sugito
 * Date: 9/21/2016
 * Time: 4:35 PM
 */

namespace App\Http\Controllers;

use Auth;
use Validator;
use LaravelLocalization;
use Illuminate\Http\Request;
use Vinkla\Hashids\Facades\Hashids;

use App\Services\WarehouseService;

class WarehouseController extends Controller
{
    private $warehouseService;

    public function __construct(WarehouseService $warehouseService)
    {
        $this->middleware('auth');
        $this->warehouseService = $warehouseService;
    }

    public function index()
    {
        return view('warehouse.index');
    }

    public function read(Request $request)
    {
        return $this->warehouseService->read();
    }

    public function store(Request $request)
    {
        Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'status' => 'required',
        ])->validate();

        if (count($request['section_name']) == 0) {
            $rules = ['section_name' => 'required'];
            $messages = ['section_name.required' => LaravelLocalization::getCurrentLocale() == "en" ? "Please provide at least 1 Lot.":"Harap isi paling tidak 1 lot"];

            Validator::make($request->all(), $rules, $messages)->validate();
        }

        $sections = [];

        for ($i = 0; $i < count($request['section_name']); $i++) {
            array_push($sections, array(
                'company_id' => Auth::user()->company->id,
                'name' => $request['section_name'][$i],
                'position' => $request['section_position'][$i],
                'capacity' => $request['section_capacity'][$i],
                'capacity_unit_id' => Hashids::decode($request['section_capacity_unit_id'][$i])[0],
                'remarks' => $request['section_remarks'][$i]
            ));
        }

        $this->warehouseService->create(
            Auth::user()->company->id,
            $request['name'],
            $request['address'],
            $request['phone_num'],
            $request['status'],
            $request['remarks'],
            $sections
        );

        return response()->json();
    }

    public function update($id, Request $request)
    {
        Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'status' => 'required',
        ])->validate();

        if (count($request['section_name']) == 0) {
            $rules = ['section_name' => 'required'];
            $messages = ['section_name.required' => LaravelLocalization::getCurrentLocale() == "en" ? "Please provide at least 1 Lot.":"Harap isi paling tidak 1 lot"];

            Validator::make($request->all(), $rules, $messages)->validate();
        }

        $sections = [];

        for ($i = 0; $i < count($request['section_name']); $i++) {
            array_push($sections, array(
                'company_id' => Auth::user()->company->id,
                'name' => $request['section_name'][$i],
                'position' => $request['section_position'][$i],
                'capacity' => $request['section_capacity'][$i],
                'capacity_unit_id' => Hashids::decode($request['section_capacity_unit_id'][$i])[0],
                'remarks' => $request['section_remarks'][$i]
            ));
        }

        $this->warehouseService->update(
            $id,
            Auth::user()->company->id,
            $request['name'],
            $request['address'],
            $request['phone_num'],
            $request['status'],
            $request['remarks'],
            $sections
        );

        return response()->json();
    }

    public function delete($id)
    {
        $this->warehouseService->delete($id);

        return response()->json();
    }
}

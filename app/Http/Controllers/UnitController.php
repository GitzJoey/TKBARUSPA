<?php
/**
 * Created by PhpStorm.
 * User: Sugito
 * Date: 9/9/2016
 * Time: 5:52 PM
 */

namespace App\Http\Controllers;

use DB;
use Exception;
use Validator;
use Illuminate\Http\Request;

use App\Services\UnitService;

class UnitController extends Controller
{
    private $unitService;

    public function __construct(UnitService $unitService)
    {
        $this->middleware('auth');
        $this->unitService = $unitService;
    }

    public function index()
    {
        return view('unit.index');
    }

    public function read(Request $request)
    {
        return $this->unitService->read();
    }

    public function store(Request $req)
    {
        Validator::make($req->all(), [
            'name' => 'required|string|max:255',
            //Sample Exception From Validation
            //'symbol' => 'required|string|min:255',
            'symbol' => 'required|string|max:255',
            'status' => 'required',
        ])->validate();

        DB::beginTransaction();
        try {
            //throw New Exception('Test Exception From Controller');
            $this->unitService->create($req['name'], $req['symbol'], $req['status'], $req['remarks']);

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
            'symbol' => 'required|string|max:255',
            'status' => 'required',
        ])->validate();

        DB::beginTransaction();
        try {
            $this->unitService->update($id, $req['name'], $req['symbol'], $req['status'], $req['remarks']);

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
            $this->unitService->delete($id);

            DB::commit();
            return response()->json();
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}

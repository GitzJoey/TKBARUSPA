<?php
/**
 * Created by PhpStorm.
 * User: Sugito
 * Date: 9/9/2016
 * Time: 5:52 PM
 */

namespace App\Http\Controllers;

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

    public function readAll(Request $request)
    {
        return $this->unitService->readAll();
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

        //Sample Force Exception
        //No Need Try Catch Wrapper In Controller
        //throw new Exception('Test Laravel Exception');

        $this->unitService->create($req['name'], $req['symbol'], $req['status'], $req['remarks']);

        return response()->json();
    }

    public function update($id, Request $req)
    {
        Validator::make($req->all(), [
            'name' => 'required|string|max:255',
            'symbol' => 'required|string|max:255',
            'status' => 'required',
        ])->validate();

        $this->unitService->update($id, $req['name'], $req['symbol'], $req['status'], $req['remarks']);

        return response()->json();
    }

    public function delete($id)
    {
        $this->unitService->delete($id);

        return response()->json();
    }
}

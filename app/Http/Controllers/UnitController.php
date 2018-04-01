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
            'symbol' => 'required|string|max:255',
            'status' => 'required|string|max:255',
        ])->validate();

        try {
            $this->unitService->create($req['name'], $req['symbol'], $req['status'], $req['remarks']);

            return response()->json();
        } catch (Exception $e) {
            return response()->json([
                'errors' => $e->getMessage()
            ]);
        }
    }

    public function update($id, Request $req)
    {
        Validator::make($req->all(), [
            'name' => 'required|string|max:255',
            'symbol' => 'required|string|max:255',
            'status' => 'required|string|max:255',
        ])->validate();

        try {
            $this->unitService->update($id, $req['name'], $req['symbol'], $req['status'], $req['remarks']);

            return response()->json();
        } catch (Exception $e) {
            return response()->json([
                'errors' => $e->getMessage()
            ]);
        }
    }

    public function delete($id)
    {


        return response()->json();
    }
}

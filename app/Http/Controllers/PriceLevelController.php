<?php
/**
 * Created by PhpStorm.
 * User: GitzJoey
 * Date: 9/22/2016
 * Time: 5:04 PM
 */

namespace App\Http\Controllers;

use DB;
use Auth;
use Exception;
use Validator;
use Illuminate\Http\Request;

use App\Services\PriceLevelService;

class PriceLevelController extends Controller
{
    private $priceLevelService;

    public function __construct(PriceLevelService $priceLevelService)
    {
        $this->middleware('auth');
        $this->priceLevelService = $priceLevelService;
    }

    public function index()
    {
        return view('price_level.index');
    }

    public function store(Request $request)
    {
        Validator::make($request->all(), [
            'type' => 'required|string|max:255',
            'weight' => 'required',
            'name' => 'required|string|max:255',
            'status' => 'required|string|max:255',
        ])->validate();

        DB::beginTransaction();
        try {
            $this->priceLevelService->create(
                Auth::user()->company->id,
                $request['type'],
                $request['weight'],
                $request['name'],
                $request['description'],
                $request['increment_value'],
                $request['percentage_value'],
                $request['status']
            );

            DB::commit();
            return response()->json();
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function read()
    {
        return $this->priceLevelService->read();
    }

    public function update($id, Request $request)
    {
        DB::beginTransaction();
        try {
            $this->priceLevelService->update(
                $id,
                Auth::user()->company->id,
                $request['type'],
                $request['weight'],
                $request['name'],
                $request['description'],
                $request['increment_value'],
                $request['percentage_value'],
                $request['status']
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
            $this->priceLevelService->delete($id);

            DB::commit();
            return response()->json();
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}

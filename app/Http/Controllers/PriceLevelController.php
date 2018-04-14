<?php
/**
 * Created by PhpStorm.
 * User: GitzJoey
 * Date: 9/22/2016
 * Time: 5:04 PM
 */

namespace App\Http\Controllers;

use App\Models\PriceLevel;

use Auth;
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

        return response()->json();
    }

    public function read()
    {
        return $this->priceLevelService->read();
    }

    public function update($id, Request $request)
    {
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

        return response()->json();
    }

    public function delete($id)
    {
        $this->priceLevelService->delete($id);

        return response()->json();
    }
}

<?php

namespace App\Http\Controllers;

use DB;
use Auth;
use Exception;
use Validator;
use Illuminate\Http\Request;
use Vinkla\Hashids\Facades\Hashids;

use App\Services\StockService;

class StockOpnameController extends Controller
{
    private $stockService;

    public function __construct(StockService $stockService)
    {
        $this->stockService = $stockService;
        $this->middleware('auth');
    }

    public function index()
    {
        return view('warehouse.stock.opname.index');
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $this->stockService->adjustStockByOpname(
                Auth::user()->company->id,
                Hashids::decode($request['stock_id'])[0],
                $request['opname_date'],
                $request['is_match'],
                floatval($request['adjusted_quantity']),
                $request['reason']
            );

            DB::commit();
            return response()->json();
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }

    }
}

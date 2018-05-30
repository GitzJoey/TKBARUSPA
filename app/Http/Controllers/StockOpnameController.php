<?php

namespace App\Http\Controllers;

use Auth;
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

    public function getCurrentStocks($warehouseId)
    {
        return $this->stockService->getAllCurrentStock($warehouseId);
    }
}

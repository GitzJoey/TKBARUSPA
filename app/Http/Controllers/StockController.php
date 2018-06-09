<?php

namespace App\Http\Controllers;

use Auth;
use Validator;
use Illuminate\Http\Request;
use Vinkla\Hashids\Facades\Hashids;

use App\Services\StockService;

class StockController extends Controller
{
    private $stockService;

    public function __construct(StockService $stockService)
    {
        $this->stockService = $stockService;
        $this->middleware('auth');
    }

    public function getCurrentStocks(Request $request)
    {
        $warehouseId = $request->has('w') ? $request->query('w'):'';

        return $this->stockService->getAllCurrentStock($warehouseId);
    }

    public function getStockAndProduct()
    {
        return $this->stockService->getStockAndProduct();
    }
}

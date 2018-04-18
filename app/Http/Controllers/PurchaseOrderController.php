<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Services\PurchaseOrderService;

class PurchaseOrderController extends Controller
{
    public function __construct(PurchaseOrderService $purchaseOrderService)
    {
        $this->middleware('auth');
        $this->purchaseOrderService = $purchaseOrderService;
    }

    public function index()
    {
        return view('purchase_order.index');
    }

    public function read(Request $request)
    {
        $date = $request->query('date');

        if ($date) {
            return $this->purchaseOrderService->searchPOByDate($date);
        } else {
            return $this->purchaseOrderService->read();
        }
    }

    public function generatePOCode()
    {
        return $this->purchaseOrderService->generatePOCode();
    }
}

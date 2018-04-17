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

    public function read()
    {
        return $this->purchaseOrderService->read();
    }

    public function generatePOCode()
    {
        return $this->purchaseOrderService->generatePOCode();
    }
}

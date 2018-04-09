<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Services\SupplierService;

class SupplierController extends Controller
{
    private $supplierService;

    public function __construct(SupplierService $supplierService)
    {
        $this->middleware('auth');
        $this->supplierService = $supplierService;
    }

    public function index()
    {
        return view('supplier.index');
    }

    public function read()
    {
        $this->supplierService->read();
    }
}

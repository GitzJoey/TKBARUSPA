<?php
/**
 * Created by PhpStorm.
 * User: gitzj
 * Date: 4/8/2018
 * Time: 3:33 AM
 */

namespace App\Http\Controllers;

use App\Services\ProductTypeService;

class ProductTypeController extends Controller
{
    private $productTypeService;

    public function __construct(ProductTypeService $productTypeService)
    {
        $this->middleware('auth');
        $this->productTypeService = $productTypeService;
    }

    public function readAll()
    {
        return $this->productTypeService->readAll();
    }
}
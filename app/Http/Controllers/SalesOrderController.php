<?php
/**
 * Created by PhpStorm.
 * User: gitzj
 * Date: 5/17/2018
 * Time: 1:33 PM
 */
namespace App\Http\Controllers;

use Auth;
use Validator;
use Illuminate\Http\Request;
use Vinkla\Hashids\Facades\Hashids;

use App\Services\SalesOrderService;

class SalesOrderController extends Controller
{
    private $salesOrderService;

    public function __construct(SalesOrderService $salesOrderService)
    {
        $this->middleware('auth');
        $this->salesOrderService = $salesOrderService;
    }
}
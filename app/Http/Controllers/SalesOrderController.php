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

    public function index()
    {
        return view('sales_order.index');
    }

    public function read(Request $request)
    {
        $date = $request->query('date');

        if ($date) {
            return $this->salesOrderService->searchSOByDate($date);
        } else {
            return $this->salesOrderService->read();
        }
    }

    public function generateSOCode()
    {
        return $this->salesOrderService->generateSOCode();
    }
}
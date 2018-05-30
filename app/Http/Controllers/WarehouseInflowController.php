<?php
/**
 * Created by PhpStorm.
 * User: Sugito
 * Date: 10/27/2016
 * Time: 10:12 AM
 */

namespace App\Http\Controllers;

use App\Services\PurchaseOrderService;
use App\Services\StockService;

use DB;
use Auth;
use Exception;
use Illuminate\Http\Request;
use Vinkla\Hashids\Facades\Hashids;

class WarehouseInflowController extends Controller
{
    private $purchaseOrderService;
    private $stockService;

    public function __construct(PurchaseOrderService $purchaseOrderService, StockService $stockService)
    {
        $this->middleware('auth');
        $this->purchaseOrderService = $purchaseOrderService;
        $this->stockService = $stockService;
    }

    public function index()
    {
        return view('warehouse.inflow.index');
    }

    public function store($id, Request $request)
    {
        DB::beginTransaction();
        try {
            $receiptsArr = array(
                'company_id' => Auth::user()->company->id,
                'po_id' => Hashids::decode($request['po_id'])[0],
                'vendor_trucking_id' => $request['vendor_trucking_id'] == '' ? 0:Hashids::decode($request['vendor_trucking_id'])[0],
                'truck_id' => $request['truck_id'] == '' ? 0:Hashids::decode($request['truck_id'])[0],
                'article_code' => '',
                'driver_name' => $request['driver_name'],
                'receipt_date' => $request['receipt_date'],
                'remarks' => $request['remarks']
            );

            $receiptDetailArr = array();
            for($i = 0; $i < count($request->input('item_id')); $i++){
                array_push($receiptDetailArr, array (
                    'company_id' => Auth::user()->company->id,
                    'item_id' => Hashids::decode($request['item_id'][$i])[0],
                    'selected_product_unit_id' => Hashids::decode($request['selected_product_unit_id'][$i])[0],
                    'base_product_unit_id' => Hashids::decode($request['base_product_unit_id'][$i])[0],
                    'conversion_value' => floatval($request['conversion_value'][$i]),
                    'brutto' => floatval($request['brutto'][$i]),
                    'netto' => floatval($request['netto'][$i]),
                    'tare' => floatval($request['tare'][$i]),
                ));
            }

            $expenseArr = array();
            for($i = 0; $i < count($request->input('expense_id')); $i++){
                array_push($expenseArr, array (
                    'name' => $request->input("expense_name.$i"),
                    'type' => $request->input("expense_type.$i"),
                    'is_internal_expense' => 1,
                    'amount' => floatval($request->input("expense_amount.$i")),
                    'remarks' => $request->input("expense_remarks.$i")
                ));
            }

            $r = $this->purchaseOrderService->addReceipt(
                Hashids::decode($request['po_id'])[0],
                $receiptsArr,
                $receiptDetailArr
            );

            $this->stockService->addStockByReceipt($r);

            $this->purchaseOrderService->addExpenses(
                Hashids::decode($request['po_id'])[0],
                $expenseArr
            );

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }

        return response()->json();
    }
}
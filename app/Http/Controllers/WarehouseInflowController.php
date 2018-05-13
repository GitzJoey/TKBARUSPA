<?php
/**
 * Created by PhpStorm.
 * User: Sugito
 * Date: 10/27/2016
 * Time: 10:12 AM
 */

namespace App\Http\Controllers;

use App\Services\WarehouseInflowService;

use DB;
use Auth;
use Exception;
use Illuminate\Http\Request;
use Vinkla\Hashids\Facades\Hashids;

class WarehouseInflowController extends Controller
{
    private $warehouseInflowService;

    public function __construct(WarehouseInflowService $warehouseInflowService)
    {
        $this->middleware('auth');
        $this->warehouseInflowService = $warehouseInflowService;
    }

    public function index()
    {
        return view('warehouse.inflow.index');
    }

    public function store($id, Request $request)
    {
        DB::beginTransaction();
        try {
            $receiptsArr = array();
            $inputtedReceiptsArr = array();
            for($i = 0; $i < count($request->input('receipt_id')); $i++){
                array_push($receiptsArr, array (
                    'receipt_date' => date('Y-m-d', strtotime($request['receipt_date'])),
                    'license_plate' => $request['license_plate'],
                    'conversion_value' => $request['conversion_value'][$i],
                    'brutto' => $request['brutto'][$i],
                    'netto' => $request['netto'][$i],
                    'tare' => $request['tare'][$i],
                    'item_id' => is_null($request['item_id'][$i]) ? '' : $request['item_id'][$i],
                    'selected_product_unit_id' => Hashids::decode($request['selected_product_unit_id'][$i])[0],
                    'base_product_unit_id' => Hashids::decode($request['base_unit_id'][$i])[0],
                    'company_id' => Auth::user()->company_id
                ));

                array_push($inputtedReceiptsArr, is_null($request['receipt_id'][$i]) ? '' : Hashids::decode($request['receipt_id'][$i])[0]);
            }

            $expenseArr = array();
            $inputtedEexpenseArr = array();
            for($i = 0; $i < count($request->input('expense_id')); $i++){
                if ($request->input('expense_id'.$i) != 0) continue;

                array_push($expenseArr, array (
                    'expense_name' => $request->input("expense_name.$i"),
                    'expense_type' => $request->input("expense_type.$i"),
                    'is_internal_expense' => true,
                    'expense_amount' => floatval(str_replace(',', '', $request->input("expense_amount.$i"))),
                    'expense_remarks' => $request->input("expense_remarks.$i")
                ));
                array_push($inputtedEexpenseArr, is_null($request['expense_id'][$i]) ? '' : Hashids::decode($request['expense_id'][$i])[0]);
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }

        return response()->json();
    }
}
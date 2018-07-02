<?php
/**
 * Created by PhpStorm.
 * User: gitzj
 * Date: 5/17/2018
 * Time: 1:33 PM
 */
namespace App\Http\Controllers;

use DB;
use Auth;
use Exception;
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

    public function store(Request $request)
    {
        Validator::make($request->all(), [
            'code'                      => 'required|string|max:255',
            'so_type'                   => 'required|string|max:255',
            'so_created'                => 'required|string|max:255',
            'shipping_date'             => 'required|string|max:255',
            'customer_type'             => 'required|string|max:255',
            'item_product_id'           => 'required',
            'item_selected_unit_id.*'   => 'required',
            'item_quantity.*'           => 'required|numeric',
            'item_price.*'              => 'required|numeric',
            'item_discount.*'           => 'required|numeric',
            'customer_id'               => 'required_if:supplier_type,SUPPLIERTYPE.R',
            'walk_in_cust'              => 'required_if:supplier_type,SUPPLIERTYPE.WI|string|max:255',
        ])->validate();

        $items = [];

        for ($i = 0; $i < count($request['item_product_id']); $i++) {
            array_push($items, array(
                    'product_id' => Hashids::decode($request["item_product_id"][$i])[0],
                    'stock_id' => Hashids::decode($request["item_stock_id"][$i])[0],
                    'company_id' => Auth::user()->company->id,
                    'selected_product_unit_id' => Hashids::decode($request["item_selected_product_unit_id"][$i])[0],
                    'base_product_unit_id' => Hashids::decode($request["base_product_unit_id"][$i])[0],
                    'conversion_value' => floatval($request["conversion_value"][$i]),
                    'quantity' => floatval($request["item_quantity"][$i]),
                    'price' => floatval($request["item_price"][$i]),
                    'discount' => floatval($request["item_discount"][$i])
                )
            );
        }

        $expenses = [];

        for($i = 0; $i < count($request['expense_name']); $i++){
            array_push($expenses, array(
                    'name' => $request["expense_name"][$i],
                    'type' => $request["expense_type"][$i],
                    'is_internal_expense' => !empty($request["is_internal_expense"][$i]),
                    'amount' => floatval($request["expense_amount"][$i]),
                    'remarks' => $request["expense_remarks"][$i]
                )
            );
        }

        DB::beginTransaction();
        try {
            $this->salesOrderService->create(
                Auth::user()->company->id,
                $request['code'],
                $request['so_type'],
                $request['so_created'],
                $request['shipping_date'],
                $request['customer_type'],
                $items,
                $expenses,
                is_null($request['customer_id']) ? 0:Hashids::decode($request['customer_id'])[0],
                $request['walk_in_cust'],
                $request['walk_in_cust_detail'],
                is_null($request['vendor_trucking_id']) ? 0:Hashids::decode($request['vendor_trucking_id'])[0],
                $request['discount'],
                $request['status'],
                $request['remarks'],
                $request['internal_remarks'],
                $request['private_remarks']
            );

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }

        return response()->json();
    }

    public function update($id, Request $request)
    {
        Validator::make($request->all(), [
            'code'                      => 'required|string|max:255',
            'so_type'                   => 'required|string|max:255',
            'so_created'                => 'required|string|max:255',
            'shipping_date'             => 'required|string|max:255',
            'customer_type'             => 'required|string|max:255',
            'item_product_id'           => 'required',
            'item_selected_unit_id.*'   => 'required',
            'item_quantity.*'           => 'required|numeric',
            'item_price.*'              => 'required|numeric',
            'item_discount.*'           => 'required|numeric',
            'customer_id'               => 'required_if:supplier_type,CUSTOMERTYPE.R',
            'walk_in_cust'              => 'required_if:supplier_type,CUSTOMERTYPE.WI|string|max:255',
        ])->validate();

        $items = [];
        $inputtedItemIds = [];

        for ($i = 0; $i < count($request['item_product_id']); $i++) {
            array_push($items, array(
                    'product_id' => Hashids::decode($request["item_product_id"][$i])[0],
                    'company_id' => Auth::user()->company->id,
                    'selected_product_unit_id' => Hashids::decode($request["item_selected_product_unit_id"][$i])[0],
                    'base_product_unit_id' => Hashids::decode($request["base_product_unit_id"][$i])[0],
                    'conversion_value' => floatval($request["conversion_value"][$i]),
                    'quantity' => floatval($request["item_quantity"][$i]),
                    'price' => floatval($request["item_price"][$i]),
                    'discount' => floatval($request["item_discount"][$i])
                )
            );
            array_push($inputtedItemIds, is_null($request["item_id"][$i]) ? '':Hashids::decode($request["item_id"][$i])[0]);
        }

        $expenses = [];
        $inputtedExpenseIds = [];

        for($i = 0; $i < count($request['expense_name']); $i++){
            array_push($expenses, array(
                    'name' => $request["expense_name"][$i],
                    'type' => $request["expense_type"][$i],
                    'is_internal_expense' => !empty($request["is_internal_expense"][$i]),
                    'amount' => floatval($request["expense_amount"][$i]),
                    'remarks' => $request["expense_remarks"][$i]
                )
            );
            array_push($inputtedExpenseIds, is_null($request["expense_id"][$i]) ? '':Hashids::decode($request["expense_id"][$i])[0]);
        }

        DB::beginTransaction();

        try {
            $this->salesOrderService->update(
                $id,
                Auth::user()->company->id,
                $request['code'],
                $request['so_type'],
                $request['so_created'],
                $request['shipping_date'],
                $request['customer_type'],
                $items,
                $inputtedItemIds,
                $expenses,
                $inputtedExpenseIds,
                is_null($request['customer_id']) ? 0:Hashids::decode($request['customer_id'])[0],
                $request['walk_in_cust'],
                $request['walk_in_cust_detail'],
                is_null($request['vendor_trucking_id']) ? 0:Hashids::decode($request['vendor_trucking_id'])[0],
                $request['discount'],
                $request['status'],
                $request['remarks'],
                $request['internal_remarks'],
                $request['private_remarks']
            );

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function getAllWaitingDeliverySO($warehouseId)
    {
        $status = 'SOSTATUS.WD';
        $warehouseId = Hashids::decode($warehouseId)[0];

        return $this->salesOrderService->getAllWaitingDeliverSO($warehouseId, $status);
    }
}
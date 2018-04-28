<?php

namespace App\Http\Controllers;

use Auth;
use Validator;
use Illuminate\Http\Request;
use Vinkla\Hashids\Facades\Hashids;

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

    public function store(Request $request)
    {
        Validator::make($request->all(), [
                'code'                      => 'required|string|max:255',
                'po_type'                   => 'required|string|max:255',
                'po_created'                => 'required|string|max:255',
                'shipping_date'             => 'required|string|max:255',
                'supplier_type'             => 'required|string|max:255',
                'item_product_id'           => 'required',
                'item_selected_unit_id.*'   => 'required',
                'item_quantity.*'           => 'required|numeric',
                'item_price.*'              => 'required|numeric',
                'discount.*'                => 'required|numeric',
                'supplier_id'               => 'required_if:supplier_type,SUPPLIERTYPE.R',
                'walk_in_supplier'          => 'required_if:supplier_type,SUPPLIERTYPE.WI|string|max:255',
                'warehouse_id'              => 'required',
                'item_disc_percent.*.*'     => 'numeric',
                'item_disc_value.*.*'       => 'numeric',
                'disc_total_percent'        => 'numeric',
                'disc_total_value'          => 'numeric',
        ])->validate();

        $items = [];

        for ($i = 0; $i < count($request['item_product_id']); $i++) {
            array_push($items, array(
                    'product_id' => Hashids::decode($request["item_product_id"][$i])[0],
                    'company_id' => Auth::user()->company->id,
                    'selected_product_unit_id' => Hashids::decode($request["item_selected_product_unit_id"][$i])[0],
                    'base_product_unit_id' => Hashids::decode($request["base_product_unit_id"][$i])[0],
                    'conversion_value' => floatval($request["conversion_value"][$i]),
                    'quantity' => floatval($request["item_quantity"][$i]),
                    'price' => floatval($request["item_price"][$i]),
                    'discount' => floatval($request["discount"][$i])
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

        $this->purchaseOrderService->create(
            $request['code'],
            $request['po_type'],
            $request['po_created'],
            $request['shipping_date'],
            $request['supplier_type'],
            $items,
            $expenses,
            $request['supplier_id'] == 0 ? 0:Hashids::decode($request['supplier_id'])[0],
            $request['walk_in_supplier'],
            $request['walk_in_supplier_detail'],
            Hashids::decode($request['warehouse_id'])[0],
            $request['disc_total_value'],
            $request['status'],
            $request['remarks'],
            $request['internal_remarks'],
            $request['private_remarks']
        );
    }
}

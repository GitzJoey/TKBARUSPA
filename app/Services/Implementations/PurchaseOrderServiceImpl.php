<?php
/**
 * Created by PhpStorm.
 * User: TKBARU
 * Date: 4/13/2018
 * Time: 9:37 PM
 */

namespace App\Services\Implementations;

use App\Models\PurchaseOrder;

use Config;
use Carbon\Carbon;

use App\Services\PurchaseOrderService;

class PurchaseOrderServiceImpl implements PurchaseOrderService
{

    public function create(
        $name,
        $symbol,
        $status,
        $remarks
    )
    {
        // TODO: Implement create() method.
    }

    public function read()
    {
        return PurchaseOrder::get();
    }

    public function update(
        $id,
        $name,
        $symbol,
        $status,
        $remarks
    )
    {
        // TODO: Implement update() method.
    }

    public function generatePOCode()
    {
        $result = '';

        do
        {
            $length = Config::get('const.TRXCODE.LENGTH');
            $generatedString = '';
            $characters = array_merge(Config::get('const.RANDOMSTRINGRANGE.ALPHABET'), Config::get('const.RANDOMSTRINGRANGE.NUMERIC'));
            $max = sizeof($characters) - 1;

            for ($i = 0; $i < $length; $i++) {
                $generatedString .= $characters[mt_rand(0, $max)];
            }

            $temp_result = strtoupper($generatedString);

            $po = PurchaseOrder::whereCode($temp_result);
            if (empty($po->first())) {
                $result = $temp_result;
            }
        } while (empty($result));

        return $result;
    }

    public function getLastPODates($limit = 50)
    {
        $po = PurchaseOrder::all()->groupBy(function ($po) {
            return $po->po_created->format('d-M-y');
        })->take($limit)->map(function($item) {
            return $item->all()[0]->po_created->format('d/m/y');
        });

        return $po;
    }

    public function searchPOByDate($date)
    {
        $date = Carbon::parse($date)->format('Y-m-d');

        $purchaseOrders = PurchaseOrder::with([ 'items.product', 'supplier.profiles', 'receipts.item.product',
            'receipts.item.selectedUnit' => function($q) { $q->with('unit')->withTrashed(); }
        ])->where('po_created', 'like', '%'.$date.'%')->get();

        return $purchaseOrders;
    }
}
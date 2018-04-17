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
}
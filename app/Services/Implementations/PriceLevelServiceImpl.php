<?php
/**
 * Created by PhpStorm.
 * User: gitzj
 * Date: 3/29/2018
 * Time: 9:35 PM
 */

namespace App\Services\Implementations;

use App\Models\PriceLevel;

use App\Services\PriceLevelService;

class PriceLevelServiceImpl implements PriceLevelService
{
    public function create(
        $company_id,
        $type,
        $weight,
        $name,
        $description,
        $increment_value,
        $percentage_value,
        $status
    )
    {
        PriceLevel::create([
            'company_id' => $company_id,
            'type' => $type,
            'weight' => $weight,
            'name' => $name,
            'description' => $description,
            'increment_value' => $increment_value,
            'percentage_value' => $percentage_value,
            'status' => $status,
        ]);
    }

    public function read()
    {
        return PriceLevel::get();
    }

    public function update(
        $id,
        $company_id,
        $type,
        $weight,
        $name,
        $description,
        $increment_value,
        $percentage_value,
        $status
    )
    {
        PriceLevel::find($id)->update([
            'company_id' => $company_id,
            'type' => $type,
            'weight' => $weight,
            'name' => $name,
            'description' => $description,
            'increment_value' => $increment_value,
            'percentage_value' => $percentage_value,
            'status' => $status
        ]);
    }

    public function delete($id)
    {
        PriceLevel::find($id)->delete();
    }
}
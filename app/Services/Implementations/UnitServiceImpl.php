<?php
/**
 * Created by PhpStorm.
 * User: gitzj
 * Date: 3/29/2018
 * Time: 9:35 PM
 */

namespace App\Services\Implementations;

use App\Models\Unit;

use App\Services\UnitService;

class UnitServiceImpl implements UnitService
{
    public function create(
        $name,
        $symbol,
        $status,
        $remarks
    )
    {
        //throw New \Exception('Exception From Services');
        Unit::create([
            'name' => $name,
            'symbol' => $symbol,
            'status' => $status,
            'remarks' => $remarks,
        ]);
    }

    public function read()
    {
        return Unit::get();
    }

    public function update(
        $id,
        $name,
        $symbol,
        $status,
        $remarks
    )
    {
        $unit = Unit::find($id);

        if (!is_null($unit)) {
            $unit->name = $name;
            $unit->symbol = $symbol;
            $unit->status = $status;
            $unit->remarks = $remarks;

            $unit->save();
        }
    }

    public function delete($id)
    {
        Unit::find($id)->delete();
    }
}
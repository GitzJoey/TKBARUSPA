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
        Unit::create([
            'name' => $name,
            'symbol' => $symbol,
            'status' => $status,
            'remarks' => $remarks,
        ]);
    }

    public function read($id)
    {
        return Unit::find($id);
    }

    public function readAll($limit = 0)
    {
        if ($limit != 0) {
            return Unit::latest()->take($limit)->get();
        } else {
            return Unit::get();
        }
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

        if (!is_null) {
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
<?php
/**
 * Created by PhpStorm.
 * User: gitzj
 * Date: 3/29/2018
 * Time: 9:35 PM
 */

namespace App\Services\Implementations;

use App\Models\Warehouse;
use App\Models\WarehouseSection;

use App\Services\WarehouseService;

class WarehouseServiceImpl implements WarehouseService
{

    public function create(
        $company_id,
        $name,
        $address,
        $phone_num,
        $status,
        $remarks,
        $sections
    )
    {
        $warehouse = new Warehouse();

        $warehouse->company_id = $company_id;
        $warehouse->name = $name;
        $warehouse->address = $address;
        $warehouse->phone_num = $phone_num;
        $warehouse->status = $status;
        $warehouse->remarks = $remarks;
        $warehouse->save();

        for ($i = 0; $i < count($sections); $i++) {
            $ws = new WarehouseSection();

            $ws->company_id = $company_id;
            $ws->name = $sections[$i]['name'];
            $ws->position = $sections[$i]['position'];
            $ws->capacity = $sections[$i]['capacity'];
            $ws->capacity_unit_id = $sections[$i]['capacity_unit_id'];
            $ws->remarks = $sections[$i]['remarks'];

            $warehouse->sections()->save($ws);
        }
    }

    public function read()
    {
        return Warehouse::with('sections.capacityUnit')->get();
    }

    public function update(
        $id,
        $company_id,
        $name,
        $address,
        $phone_num,
        $status,
        $remarks,
        $sections
    )
    {
        $warehouse = Warehouse::find($id);

        $warehouse->sections->each(function($s) { $s->delete(); });

        for ($i = 0; $i < count($sections); $i++) {
            $ws = new WarehouseSection();

            $ws->company_id = $sections[$i]['company_id'];
            $ws->name = $sections[$i]['name'];
            $ws->position = $sections[$i]['position'];
            $ws->capacity = $sections[$i]['capacity'];
            $ws->capacity_unit_id = $sections[$i]['capacity_unit_id'];
            $ws->remarks = $sections[$i]['remarks'];

            $warehouse->sections()->save($ws);
        }

        $warehouse->company_id = $company_id;
        $warehouse->name = $name;
        $warehouse->address = $address;
        $warehouse->phone_num = $phone_num;
        $warehouse->status = $status;
        $warehouse->remarks = $remarks;

        $warehouse->save();
    }

    public function delete($id)
    {
        $warehouse = Warehouse::find($id);
        $warehouse->sections->each(function($s) { $s->delete(); });
        $warehouse->delete();
    }
}
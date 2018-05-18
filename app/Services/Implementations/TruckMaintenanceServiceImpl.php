<?php
/**
 * Created by PhpStorm.
 * User: gitzj
 * Date: 3/29/2018
 * Time: 9:35 PM
 */

namespace App\Services\Implementations;

use App\Models\TruckMaintenance;

use App\Services\TruckMaintenanceService;

class TruckMaintenanceServiceImpl implements TruckMaintenanceService
{
    public function create(
        $company_id,
        $truck_id,
        $maintenance_date,
        $maintenance_type,
        $cost,
        $odometer,
        $remarks
    )
    {
        DB::beginTransaction();
        try {
            $tm = new TruckMaintenance;

            $tm->company_id = $company_id;
            $tm->truck_id = $truck_id;
            $tm->maintenance_date = $maintenance_date;
            $tm->maintenance_type = $maintenance_type;
            $tm->cost = $cost;
            $tm->odometer = $odometer;
            $tm->remarks = $remarks;

            $tm->save();
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function read()
    {
        return TruckMaintenance::with('truck')->get();
    }

    public function update(
        $id,
        $company_id,
        $truck_id,
        $maintenance_date,
        $maintenance_type,
        $cost,
        $odometer,
        $remarks
    )
    {
        DB::beginTransaction();
        try {
            $truckMaintenance = TruckMaintenance::find($id);

            if (!is_null($truckMaintenance)) {
                $truckMaintenance->company_id = $company_id;
                $truckMaintenance->truck_id = $truck_id;
                $truckMaintenance->maintenance_date = $maintenance_date;
                $truckMaintenance->maintenance_type = $maintenance_type;
                $truckMaintenance->cost = $cost;
                $truckMaintenance->odometer = $odometer;
                $truckMaintenance->remarks = $remarks;

                $truckMaintenance->save();
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function delete($id)
    {
        TruckMaintenance::find($id)->delete();
    }
}
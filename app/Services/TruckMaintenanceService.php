<?php

namespace App\Services; 
 
interface TruckMaintenanceService 
{ 
    public function create( 
        $company_id,
        $truck_id,
        $maintenance_date,
        $maintenance_type,
        $cost,
        $odometer,
        $remarks
    ); 
    public function read(); 
    public function update( 
        $id,
        $company_id,
        $truck_id,
        $maintenance_date,
        $maintenance_type,
        $cost,
        $odometer,
        $remarks
    ); 
    public function delete($id); 
}
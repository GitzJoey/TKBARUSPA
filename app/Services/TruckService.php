<?php

namespace App\Services; 
 
interface TruckService 
{ 
    public function create( 
        $company_id, 
        $type, 
        $plate_number, 
        $inspection_date, 
        $driver, 
        $status, 
        $remarks
    ); 
    public function read(); 
    public function update( 
        $id, 
        $company_id, 
        $type, 
        $plate_number, 
        $inspection_date, 
        $driver, 
        $status, 
        $remarks
    ); 
    public function delete($id); 
}
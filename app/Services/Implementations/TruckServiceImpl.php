<?php 
/** 
 * Created by PhpStorm. 
 * User: gitzj 
 * Date: 3/29/2018 
 * Time: 9:35 PM 
 */ 
 
namespace App\Services\Implementations; 
 
use App\Models\Truck; 

use App\Services\UnitService; 
 
class TruckServiceImpl implements TruckService 
{ 
 
    public function create( 
        $store_id, 
        $type, 
        $plate_number, 
        $inspection_date, 
        $driver, 
        $status, 
        $remarks 
    ) 
    { 
        Truck::create([ 
            'store_id' => $store_id, 
            'type' => $type, 
            'plate_number' => $plate_number, 
            'inspection_date' => $inspection_date, 
            'driver' => $driver, 
            'status' => $status, 
            'remarks' => $remarks, 
        ]); 
    } 
 
    public function read() 
    { 
        return Truck::get(); 
    } 
 
    public function update( 
        $id, 
        $store_id, 
        $type, 
        $plate_number, 
        $inspection_date, 
        $driver, 
        $status, 
        $remarks 
    ) 
    { 
        $truck = Truck::find($id); 
 
        if (!is_null($truck)) { 
            $truck->store_id = $store_id; 
            $truck->type = $type; 
            $truck->plate_number = $plate_number; 
            $truck->inspection_date = $inspection_date; 
            $truck->driver = $driver; 
            $truck->status = $status; 
            $truck->remarks = $remarks; 
+
 
            $truck->save(); 
        } 
    } 
 
    public function delete($id) 
    { 
        Truck::find($id)->delete(); 
    } 
}
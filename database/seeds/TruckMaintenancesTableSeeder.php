<?php

use App\Models\Truck;
use App\Models\TruckMaintenance;
use Illuminate\Database\Seeder;

class TruckMaintenancesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $trucks = Truck::all();
        $maintenanceTypes = Config::get('lookup.VALUE.TRUCK_MAINTENANCE_TYPE');

        for ($i = 1; $i < 11; $i++) {
            foreach ($trucks as $key => $truck){
                foreach ($maintenanceTypes as $key => $maintenanceType){
                    $truckMaintenance = [
                        'company_id' => 1,
                        'truck_id' => $truck->id,
                        'maintenance_date' => date('Y-m-d', strtotime( '+'.mt_rand(0,120).' days')),
                        'maintenance_type' => $maintenanceType,
                        'cost' => mt_rand(100000, 10000000),
                        'odometer' => mt_rand(3000, 10000000),
                        'remarks' => "Dummy maintenance ".$i
                    ];

                    TruckMaintenance::create($truckMaintenance);
                }
            }
        }
    }
}

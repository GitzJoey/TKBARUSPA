<?php

use App\Models\Truck;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class TrucksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $truckTypes = Config::get('lookup.VALUE.TRUCK_TYPE');

        $index = 0;
        foreach ($truckTypes as $key => $truckType) {
            $index++;
            $truck = [
                'company_id' => 1,
                'vendor_trucking_id' => $index,
                'type' => $truckType,
                'license_plate' => "B 100$index AG",
                'inspection_date' => Carbon::yesterday(),
                'driver' => "Driver $index",
                'remarks' => "Dummy truck $index"
            ];

            Truck::create($truck);
        }
    }
}

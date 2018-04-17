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

        foreach ($truckTypes as $key => $truckType){
            $truck = [
                'company_id' => 1,
                'type' => $truckType,
                'plate_number' => "B 100$key AG",
                'inspection_date' => Carbon::yesterday(),
                'driver' => "Driver $key",
                'status' => 'STATUS.ACTIVE',
                'remarks' => "Dummy truck $key"
            ];

            Truck::create($truck);
        }
    }
}

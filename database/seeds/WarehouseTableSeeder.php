<?php

use Illuminate\Database\Seeder;

use App\Models\Unit;
use App\Models\Warehouse;
use App\Models\WarehouseSection;

class WarehouseTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $warehouses1 = new Warehouse();
        $warehouses1->company_id = 1;
        $warehouses1->name = 'Gudang Utama';
        $warehouses1->address = 'Jl. TMP Taruna no. 74';
        $warehouses1->status = 'STATUS.ACTIVE';
        $warehouses1->phone_num = '085883227507';
        $warehouses1->save();

        for ($s = 0; $s < 5; $s++) {
            $wc = new WarehouseSection();
            $wc->company_id = 1;
            $wc->name = 'Section '.$s;
            $wc->position = '12345';
            $wc->capacity = '1000';
            $wc->capacity_unit_id = 1;

            $warehouses1->sections()->save($wc);
        }

        $warehouses2 = new Warehouse();
        $warehouses2->company_id = 1;
        $warehouses2->name = 'Gudang Tambahan';
        $warehouses2->address = 'Jl. TMP Taruna no. 75';
        $warehouses2->status = 'STATUS.ACTIVE';
        $warehouses2->phone_num = '085883227507';
        $warehouses2->save();

        for ($s = 0; $s < 5; $s++) {
            $wc = new WarehouseSection();
            $wc->company_id = 1;
            $wc->name = 'Section '.$s;
            $wc->position = '12345';
            $wc->capacity = '1000';
            $wc->capacity_unit_id = 1;

            $warehouses2->sections()->save($wc);
        }
    }
}

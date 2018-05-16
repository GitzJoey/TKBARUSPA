<?php

use Illuminate\Database\Seeder;

use App\Models\VendorTrucking;

class VendorTruckingTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $vts = [
            [
                'company_id' => 1,
                'name' => 'Default Vendor',
                'address' => 'Jl. Ahmad Yani no. 17',
                'tax_id' => '123-123-123-123',
                'status' => 'STATUS.ACTIVE',
                'maintenance_by_company' => 1
            ],
            [
                'company_id' => 1,
                'name' => 'External Vendor',
                'address' => 'Jl. Yani no. 117',
                'tax_id' => '445-445-445-445',
                'status' => 'STATUS.ACTIVE',
                'maintenance_by_company' => 0
            ],
            [
                'company_id' => 1,
                'name' => 'Other External Vendor 3',
                'address' => 'Jl. Ahmad Dhani no. 69',
                'tax_id' => '123-123-123-123',
                'status' => 'STATUS.ACTIVE',
                'maintenance_by_company' => 0
            ]
        ];

        foreach ($vts as $key => $value) {
            VendorTrucking::create($value);
        }
    }
}

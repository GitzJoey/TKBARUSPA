<?php

use App\Models\ProductType;

use Illuminate\Database\Seeder;

class ProductTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $prdType = [
            [
                'company_id' => 1,
                'name' => 'Product Type 1',
                'short_code' => 'PRD1',
                'description' => 'Product Type 1',
                'status' => 'STATUS.ACTIVE'
            ],
            [
                'company_id' => 1,
                'name' => 'Product Type 2',
                'short_code' => 'PRD2',
                'description' => 'Product Type 2',
                'status' => 'STATUS.ACTIVE'
            ],
            [
                'company_id' => 1,
                'name' => 'Product Type 3',
                'short_code' => 'PRD3',
                'description' => 'Product Type 3',
                'status' => 'STATUS.ACTIVE'
            ],
            [
                'company_id' => 1,
                'name' => 'Product Type 4',
                'short_code' => 'PRD4',
                'description' => 'Product Type 4',
                'status' => 'STATUS.ACTIVE'
            ],
        ];

        foreach ($prdType as $key => $value) {
            ProductType::create($value);
        }
    }
}

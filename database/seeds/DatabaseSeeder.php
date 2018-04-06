<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        $this->call(LaratrustSeeder::class);
        $this->call(OverrideLaratrustDefaultUser::class);
        $this->call(DefaultUnitTableSeeder::class);
        //$this->call(PhoneProviderTableSeeder::class);

        /* DUMMY DATA */
        if (App::environment('local', 'dev')) {

            $this->command->info('Local/Development Enviroment Detected. Starting Dummy Data Seeder...');

            $this->call(BankTableSeeder::class);
            //$this->call(ProductTableSeeder::class);
            //$this->call(ProductTypeTableSeeder::class);
            //$this->call(SupplierTableSeeder::class);
            //$this->call(CustomerTableSeeder::class);
            //$this->call(VendorTruckingTableSeeder::class);
            //$this->call(WarehouseTableSeeder::class);
            //$this->call(PriceLevelTableSeeder::class);
            //$this->call(TrucksTableSeeder::class);
            //$this->call(TruckMaintenancesTableSeeder::class);
        }

    }
}

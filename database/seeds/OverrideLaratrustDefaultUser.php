<?php

use Illuminate\Database\Seeder;

class OverrideLaratrustDefaultUser extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $usr_administrator = \App\User::whereEmail('administrator@app.com')->first();
        $usr_users = \App\User::whereEmail('users@app.com')->first();

        if ($usr_administrator) {
            $usr_administrator->active = false;
            $usr_administrator->save();
        }

        if ($usr_users) {
            $usr_users->active = false;
            $usr_users->save();
        }
    }
}

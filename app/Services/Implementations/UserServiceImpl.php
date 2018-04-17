<?php
/**
 * Created by PhpStorm.
 * User: TKBARU
 * Date: 4/15/2018
 * Time: 1:53 PM
 */
namespace App\Services\Implementations;

use App\User;
use App\Models\Role;
use App\Models\Profile;
use App\Models\PhoneNumber;

use DB;
use Exception;
use Carbon\Carbon;

use App\Services\UserService;

class UserServiceImpl implements UserService
{
    public function create(
        $name,
        $email,
        $password,
        $roles,
        $company,
        $profile
    )
    {
        DB::beginTransaction();
        try {
            $usr = new User();
            $usr->name = $name;
            $usr->email = $email;
            $usr->password = bcrypt($password);
            $usr->company_id = $company;

            $usr->created_at = Carbon::now();
            $usr->updated_at = Carbon::now();

            $pa = new Profile();
            $pa->first_name = $profile['first_name'];
            $pa->last_name = $profile['last_name'];
            $pa->address = $profile['address'];
            $pa->ic_num = $profile['ic_num'];

            $usr->profile()->save($pa);

            for ($j = 0; $j < count($profile['phone_numbers']); $j++) {
                $ph = new PhoneNumber();
                $ph->phone_provider_id = $profile['phone_numbers'][$j]['phone_provider_id'];
                $ph->number = $profile['phone_numbers'][$j]['number'];
                $ph->remarks = $profile['phone_numbers'][$j]['remarks'];

                $pa->phoneNumbers()->save($ph);
            }

            $usr->save();
            $usr->roles()->attach(Role::whereName($roles)->get());

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        };
    }

    public function read()
    {
        return User::with('profile.phoneNumbers', 'company', 'roles')->get();
    }

    public function update(
        $id,
        $name,
        $email,
        $password,
        $roles,
        $company,
        $profile
    )
    {
        DB::beginTransaction();
        try {
            $usr = User::find($id);
            $usr->name = $name;
            $usr->email = $email;
            $usr->company_id = $company;

            if (!empty($password)) {
                $usr->password = bcrypt($password);
            }

            $usr->updated_at = Carbon::now();

            $usr->save();

            $role_id = Role::whereName($roles)->first()->id;
            $usr->roles()->sync([$role_id]);

            DB::commit();
        } catch(Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function delete($id)
    {

    }
}
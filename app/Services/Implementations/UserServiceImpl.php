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

use Carbon\Carbon;

use App\Services\UserService;

class UserServiceImpl implements UserService
{
    public function create(
        $name,
        $email,
        $password,
        $rolesId,
        $active,
        $company,
        $profile
    )
    {
        $usr = new User();
        $usr->name = $name;
        $usr->email = $email;
        $usr->password = bcrypt($password);
        $usr->company_id = $company;
        $usr->active = $active;

        $usr->created_at = Carbon::now();
        $usr->updated_at = Carbon::now();

        $usr->save();

        $pa = new Profile();
        $pa->first_name = $profile[0]['first_name'];
        $pa->last_name = $profile[0]['last_name'];
        $pa->address = $profile[0]['address'];
        $pa->ic_num = $profile[0]['ic_num'];

        $usr->profile()->save($pa);

        for ($j = 0; $j < count($profile[0]['phone_numbers']); $j++) {
            $ph = new PhoneNumber();
            $ph->phone_provider_id = $profile[0]['phone_numbers'][$j]['phone_provider_id'];
            $ph->number = $profile[0]['phone_numbers'][$j]['number'];
            $ph->remarks = $profile[0]['phone_numbers'][$j]['remarks'];

            $pa->phoneNumbers()->save($ph);
        }

        $usr->attachRole(Role::whereId($rolesId)->first());
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
        $rolesId,
        $active,
        $company,
        $profile
    )
    {
        $usr = User::find($id);
        $usr->name = $name;
        $usr->email = $email;
        $usr->company_id = $company;
        $usr->active = $active;

        if (!empty($password)) {
            $usr->password = bcrypt($password);
        }

        $usr->updated_at = Carbon::now();

        $usr->save();

        $pa = $usr->profile()->first();
        $pa->phoneNumbers()->delete();

        for ($j = 0; $j < count($profile[0]['phone_numbers']); $j++) {
            $ph = new PhoneNumber();
            $ph->phone_provider_id = $profile[0]['phone_numbers'][$j]['phone_provider_id'];
            $ph->number = $profile[0]['phone_numbers'][$j]['number'];
            $ph->remarks = $profile[0]['phone_numbers'][$j]['remarks'];

            $pa->phoneNumbers()->save($ph);
        }

        $pa->first_name = $profile[0]['first_name'];
        $pa->last_name = $profile[0]['last_name'];
        $pa->address = $profile[0]['address'];
        $pa->ic_num = $profile[0]['ic_num'];
        $pa->save();

        $rolePrevious = $usr->roles()->first();
        $roleCurrent = Role::whereId($rolesId)->first();

        $usr->detachRole($rolePrevious->id);
        $usr->attachRole($roleCurrent->id);
    }

    public function delete($id)
    {
        $usr = User::whereId($id)->first();

        $usr->active = false;
        $usr->save();
    }
}
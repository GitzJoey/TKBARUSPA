<?php
/**
 * Created by PhpStorm.
 * User: gitzj
 * Date: 4/16/2018
 * Time: 9:51 PM
 */
namespace App\Services\Implementations;

use App\Models\Role;
use App\Models\Permission;

use DB;
use Exception;

use App\Services\RolesService;

class RolesServiceImpl implements RolesService
{
    public function create(
        $name,
        $display_name,
        $description,
        $permission
    )
    {
        DB::beginTransaction();

        try {
            $role = new Role();
            $role->name = $name;
            $role->display_name = $display_name;
            $role->description = $description;
            $role->save();

            foreach ($permission as $pl) {
                $role->permissions()->attach($pl);
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        };
    }

    public function read()
    {
        return Role::get();
    }

    public function update(
        $id,
        $name,
        $display_name,
        $description,
        $permission
    )
    {
        DB::beginTransaction();

        try {
            $role = Role::with('permissions')->where('id', '=', $id)->first();
            $pl = Permission::whereIn('id', $permission)->get();

            $role->permissions()->sync($pl);

            $role->update([
                'name' => $name,
                'display_name' => $display_name,
                'description' => $description,
            ]);
        } catch (Exception $e) {
            throw $e;
        };

    }

    public function delete($id)
    {
        $role = Role::find($id);

        $role->permissions()->attach([]);

        $role->delete();
    }
}
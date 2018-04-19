<?php
/**
 * Created by PhpStorm.
 * User: gitzj
 * Date: 4/16/2018
 * Time: 9:48 PM
 */

namespace App\Services;

interface RoleService
{
    public function create(
        $name,
        $display_name,
        $description,
        $permission
    );
    public function read();
    public function update(
        $id,
        $name,
        $display_name,
        $description,
        $permission,
        $inputtedPermission
    );
    public function delete($id);

    public function getAllPermissions();
}
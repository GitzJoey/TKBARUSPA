<?php
/**
 * Created by PhpStorm.
 * User: TKBARU
 * Date: 4/15/2018
 * Time: 1:49 PM
 */

namespace App\Services;

interface UserService
{
    public function create(
        $name,
        $email,
        $password,
        $rolesId,
        $active,
        $company,
        $profile
    );
    public function read();
    public function update(
        $id,
        $name,
        $email,
        $password,
        $rolesId,
        $active,
        $company,
        $profile
    );
    public function delete($id);
}
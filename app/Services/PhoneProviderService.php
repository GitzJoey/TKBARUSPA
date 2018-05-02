<?php
/**
 * Created by PhpStorm.
 * User: gitzj
 * Date: 3/29/2018
 * Time: 9:29 PM
 */

namespace App\Services;

interface PhoneProviderService
{
    public function create(
        $name,
        $short_name,
        $status,
        $remarks,
        $prefixes
    );
    public function read();
    public function update(
        $id,
        $name,
        $symbol,
        $status,
        $remarks,
        $prefixes
    );
    public function delete($id);
}
<?php
/**
 * Created by PhpStorm.
 * User: gitzj
 * Date: 3/29/2018
 * Time: 9:29 PM
 */

namespace App\Services;

interface UnitService
{
    public function create(
        $name,
        $symbol,
        $status,
        $remarks
    );
    public function read($id);
    public function readAll($limit = 0);
    public function update(
        $id,
        $name,
        $symbol,
        $status,
        $remarks
    );
    public function delete($id);
}
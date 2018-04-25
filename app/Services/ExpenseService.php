<?php
/**
 * Created by PhpStorm.
 * User: gitzj
 * Date: 3/29/2018
 * Time: 9:29 PM
 */

namespace App\Services;

interface ExpenseService
{
    public function create(
        $parent,
        $name,
        $type,
        $internal,
        $remarks,
        $amount
    );
    public function read();
    public function update(
        $id,
        $parent,
        $name,
        $type,
        $internal,
        $remarks,
        $amount
    );
    public function delete($id);
}
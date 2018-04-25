<?php
/**
 * Created by PhpStorm.
 * User: gitzj
 * Date: 4/25/2018
 * Time: 8:38 PM
 */

namespace App\Services\Implementations;

use App\Services\ExpenseService;

class ExpenseServiceImpl implements ExpenseService
{
    public function create(
        $parent,
        $name,
        $type,
        $internal,
        $remarks,
        $amount
    )
    {
        // TODO: Implement create() method.
    }

    public function read()
    {
        // TODO: Implement read() method.
    }

    public function update(
        $id,
        $parent,
        $name,
        $type,
        $internal,
        $remarks,
        $amount
    )
    {
        // TODO: Implement update() method.
    }

    public function delete($id)
    {
        // TODO: Implement delete() method.
    }
}
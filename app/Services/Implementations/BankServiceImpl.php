<?php
/**
 * Created by PhpStorm.
 * User: gitzj
 * Date: 3/29/2018
 * Time: 9:35 PM
 */

namespace App\Services\Implementations;

use App\Models\Bank;

use App\Services\BankService;

class BankServiceImpl implements BankService
{

    public function create(
        $name,
        $short_name,
        $branch,
        $branch_code,
        $status,
        $remarks
    )
    {

    }

    public function read()
    {
        return Bank::get();
    }

    public function update(
        $id,
        $name,
        $short_name,
        $branch,
        $branch_code,
        $status,
        $remarks
    )
    {

    }

    public function delete($id)
    {

    }
}
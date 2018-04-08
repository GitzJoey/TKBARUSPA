<?php
/**
 * Created by PhpStorm.
 * User: gitzj
 * Date: 3/29/2018
 * Time: 9:29 PM
 */

namespace App\Services;

interface BankService
{
    public function create(
        $name,
        $short_name,
        $branch,
        $branch_code,
        $status,
        $remarks
    );
    public function read();
    public function update(
        $id,
        $name,
        $short_name,
        $branch,
        $branch_code,
        $status,
        $remarks
    );
    public function delete($id);
}
<?php
/**
 * Created by PhpStorm.
 * User: gitzj
 * Date: 3/29/2018
 * Time: 9:29 PM
 */

namespace App\Services;

interface PriceLevelService
{
    public function create(
        $company_id,
        $type,
        $weight,
        $name,
        $description,
        $increment_value,
        $percentage_value,
        $status
    );
    public function read();
    public function update(
        $id,
        $company_id,
        $type,
        $weight,
        $name,
        $description,
        $increment_value,
        $percentage_value,
        $status
    );
    public function delete($id);
}
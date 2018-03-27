<?php
/**
 * Created by PhpStorm.
 * User: gitzj
 * Date: 3/22/2018
 * Time: 12:50 AM
 */

namespace App\Services\Implementations;

use App\Services\CompanyService;

class CompanyServiceImpl implements CompanyService
{

    public function create(
        $name,
        $address,
        $latitude,
        $longitude,
        $phone_num,
        $fax_num,
        $tax_id,
        $status,
        $is_default,
        $frontweb,
        $image_filename,
        $remarks,
        $date_format,
        $time_format,
        $thousand_separator,
        $decimal_separator,
        $decimal_digit,
        $ribbon
    )
    {
        // TODO: Implement create() method.
    }

    public function read($id)
    {
        // TODO: Implement read() method.
    }

    public function readAll($limit = 0)
    {
        // TODO: Implement readAll() method.
    }

    public function update(
        $name,
        $address,
        $latitude,
        $longitude,
        $phone_num,
        $fax_num,
        $tax_id,
        $status,
        $is_default,
        $frontweb,
        $image_filename,
        $remarks,
        $date_format,
        $time_format,
        $thousand_separator,
        $decimal_separator,
        $decimal_digit,
        $ribbon
    )
    {
        // TODO: Implement update() method.
    }

    public function delete($id)
    {
        // TODO: Implement delete() method.
    }

    public function createDefaultCompany($companyName)
    {
        // TODO: Implement createDefaultCompany() method.
    }

    public function setDefaultCompany($id)
    {
        // TODO: Implement setDefaultCompany() method.
    }
}

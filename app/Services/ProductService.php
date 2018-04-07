<?php
/**
 * Created by PhpStorm.
 * User: gitzj
 * Date: 3/29/2018
 * Time: 9:29 PM
 */

namespace App\Services;

interface ProductService
{
    public function create(
        $company_id,
        $product_type_id,
        $name,
        $image_filename,
        $short_code,
        $barcode,
        $minimal_in_stock,
        $description,
        $status,
        $remarks
    );
    public function read($id);
    public function readAll($limit = 0, $productId = 0);
    public function update(
        $id,
        $company_id,
        $product_type_id,
        $name,
        $image_filename,
        $short_code,
        $barcode,
        $minimal_in_stock,
        $description,
        $status,
        $remarks
    );
    public function delete($id);
}
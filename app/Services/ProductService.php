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
        $productCategories,
        $name,
        $image_filename,
        $short_code,
        $barcode,
        $productUnits,
        $minimal_in_stock,
        $description,
        $status,
        $remarks
    );
    public function read($productName = '');
    public function readAll();
    public function update(
        $id,
        $company_id,
        $product_type_id,
        $productCategories,
        $name,
        $image_filename,
        $short_code,
        $barcode,
        $productUnits,
        $minimal_in_stock,
        $description,
        $status,
        $remarks
    );
    public function delete($id);
}
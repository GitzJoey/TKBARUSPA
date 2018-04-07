<?php
/**
 * Created by PhpStorm.
 * User: gitzj
 * Date: 4/8/2018
 * Time: 12:34 AM
 */

namespace App\Services\Implementations;

use Config;

use App\Models\Product;

use App\Services\ProductService;

class ProductServiceImpl implements ProductService
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
    )
    {
        // TODO: Implement create() method.
    }

    public function read($id)
    {
        // TODO: Implement read() method.
    }

    public function readAll($limit = 0, $productId = 0)
    {
        $product = [];
        if ($productId != 0) {
            $product = Product::with('productType')->where('name', 'like', '%'.$productId.'%')
                ->paginate(Config::get('const.PAGINATION'));
        } else {
            $product = Product::with('productType')->paginate(Config::get('const.PAGINATION'));
        }

        return $product;
    }

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
    )
    {
        // TODO: Implement update() method.
    }

    public function delete($id)
    {
        // TODO: Implement delete() method.
    }
}
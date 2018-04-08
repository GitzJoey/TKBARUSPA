<?php
/**
 * Created by PhpStorm.
 * User: gitzj
 * Date: 4/8/2018
 * Time: 12:34 AM
 */

namespace App\Services\Implementations;

use DB;
use Config;
use Intervention\Image\Facades\Image;

use App\Models\Product;

use App\Services\ProductService;

class ProductServiceImpl implements ProductService
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
    )
    {
        $imageName = '';

        if (!$image_filename) {
            $imageName = time() . '.' . $image_filename->getClientOriginalExtension();
            $path = public_path('images') . '/' . $imageName;

            Image::make($image_filename->getRealPath())->resize(160, 160)->save($path);
        }

        DB::beginTransaction();
        try {
            $product = new Product;
            $product->company_id = $company_id;
            $product->product_type_id = $product_type_id;
            $product->name = $name;
            $product->image_filename = $imageName;
            $product->short_code = $short_code;
            $product->barcode = $barcode;
            $product->minimal_in_stock = $minimal_in_stock;
            $product->description = $description;
            $product->status = $status;
            $product->remarks = $remarks;

            $product->save();

            for ($i = 0; $i < count($productUnits); $i++) {
                $punit = new ProductUnit();
                $punit->unit_id = $productUnits[$i]['unit_id'];
                $punit->is_base = $productUnits[$i]['is_base'];
                $punit->conversion_value = $productUnits[$i]['conversion_value'];
                $punit->remarks = $productUnits[$i]['punit_remarks'];

                $product->productUnits()->save($punit);
            }

            for ($j = 0; $j < count($productCategories); $j++) {
                $pcat = new ProductCategory();
                $pcat->company_id = Auth::user()->company->id;
                $pcat->code = $productCategories[$j]['cat_code'];
                $pcat->name = $productCategories[$j]['cat_name'];
                $pcat->description = $productCategories[$j]['cat_description'];
                $pcat->level = $productCategories[$j]['cat_level'];

                $product->productCategories()->save($pcat);
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function read($id)
    {
        // TODO: Implement read() method.
    }

    public function readAll($limit = 0, $productId = 0)
    {
        $product = [];
        if ($productId != 0) {
            $product = Product::with('productType', 'productCategories', 'productUnits.unit')->where('name', 'like', '%'.$productId.'%')
                ->paginate(Config::get('const.PAGINATION'));
        } else {
            $product = Product::with('productType', 'productCategories', 'productUnits.unit')->paginate(Config::get('const.PAGINATION'));
        }

        return $product;
    }

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
    )
    {
        DB::beginTransaction();

        try {
            $product = Product::find($id);

            if (!empty($product->image_path)) {
                if (!empty($data['image_path'])) {
                    $imageName = time() . '.' . $data['image_path']->getClientOriginalExtension();
                    $path = public_path('images') . '/' . $imageName;

                    Image::make($data['image_path']->getRealPath())->resize(160, 160)->save($path);
                } else {
                    $imageName = $product['image_path'];
                }
            } else {
                if (!empty($data['image_path'])) {
                    $imageName = time() . '.' . $data['image_path']->getClientOriginalExtension();
                    $path = public_path('images') . '/' . $imageName;

                    Image::make($data['image_path']->getRealPath())->resize(160, 160)->save($path);
                } else {
                    $imageName = '';
                }
            }

            $product->productUnits->each(function($pu) { $pu->delete(); });

            $pu = array();
            for ($i = 0; $i < count($data['unit_id']); $i++) {
                $punit = new ProductUnit();
                $punit->unit_id = $data['unit_id'][$i];
                $punit->is_base = $data['is_base'][$i] === 'true' ? true:false;
                $punit->conversion_value = $data['conversion_value'][$i];
                $punit->remarks = empty($data['unit_remarks'][$i]) ? '' : $data['unit_remarks'][$i];

                array_push($pu, $punit);
            }

            $product->productUnits()->saveMany($pu);

            $product->productCategories->each(function($pc) { $pc->delete(); });

            $pclist = array();
            for ($j = 0; $j  < count($data['cat_level']); $j++) {
                $pcat = new ProductCategory();
                $pcat->store_id = Auth::user()->store->id;
                $pcat->code = $data['cat_code'][$j];
                $pcat->name = $data['cat_name'][$j];
                $pcat->description = $data['cat_description'][$j];
                $pcat->level = $data['cat_level'][$j];

                array_push($pclist, $pcat);
            }

            $product->productCategories()->saveMany($pclist);

            $product->update([
                'product_type_id' => $data['type'],
                'name' => $data['name'],
                'short_code' => $data['short_code'],
                'description' => $data['description'],
                'image_path' => $imageName,
                'status' => $data['status'],
                'remarks' => $data['remarks'],
                'barcode' => $data['barcode'],
                'minimal_in_stock' => $data['minimal_in_stock'],
            ]);

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function delete($id)
    {
        // TODO: Implement delete() method.
    }
}
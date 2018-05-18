<?php
/**
 * Created by PhpStorm.
 * User: gitzj
 * Date: 4/8/2018
 * Time: 3:29 AM
 */

namespace App\Services\Implementations;

use App\Models\ProductType;

use App\Services\ProductTypeService;

class ProductTypeServiceImpl implements ProductTypeService
{
	public function create(
		$company_id,
		$name,
		$short_code,
		$description,
		$status
	)
	{
        $pt = new ProductType;

        $pt->company_id = $company_id;
        $pt->name = $name;
        $pt->short_code = $short_code;
        $pt->description = $description;
        $pt->status = $status;

        $pt->save();
	}

	public function read()
	{
		return ProductType::get();
	}

	public function update(
		$id,
		$company_id,
		$name,
		$short_code,
		$description,
		$status
	)
	{
        $pt = ProductType::find($id);

        if(!is_null($pt)) {
            $pt->company_id = $company_id;
            $pt->name = $name;
            $pt->short_code = $short_code;
            $pt->description = $description;
            $pt->status = $status;

            $pt->save();
        }
	}

	public function delete($id)
	{
		ProductType::find($id)->delete();
	}
}

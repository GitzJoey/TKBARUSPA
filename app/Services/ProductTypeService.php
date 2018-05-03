<?php
/**
 * Created by PhpStorm.
 * User: gitzj
 * Date: 3/29/2018
 * Time: 9:29 PM
 */

namespace App\Services;

interface ProductTypeService
{
	public function create(
		$company_id,
		$name,
		$short_code,
		$description,
		$status
	);
	public function read();
	public function update(
		$id,
		$company_id,
		$name,
		$short_code,
		$description,
		$status
	);
	public function delete($id);
}
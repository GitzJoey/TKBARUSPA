<?php
/**
 * Created by PhpStorm.
 * User: gitzj
 * Date: 3/29/2018
 * Time: 9:35 PM
 */

namespace App\Services\Implementations;

use App\Models\PhoneProvider;

use App\Services\PhoneProviderService;

class PhoneProviderServiceImpl implements PhoneProviderService
{

    public function create(
        $name,
        $short_code,
        $status,
        $remarks
    )
    {
        PhoneProvider::create([
            'name' => $name,
            'short_code' => $short_code,
            'status' => $status,
            'remarks' => $remarks,
        ]);
    }

    public function read()
    {
        return PhoneProvider::get();
    }

    public function update(
        $id,
        $name,
        $short_code,
        $status,
        $remarks
    )
    {
        $ph = PhoneProvider::find($id);

        if (!is_null($ph)) {
            $ph->name = $name;
            $ph->short_code = $short_code;
            $ph->status = $status;
            $ph->remarks = $remarks;

            $ph->save();
        }
    }

    public function delete($id)
    {
        PhoneProvider::find($id)->delete();
    }
}
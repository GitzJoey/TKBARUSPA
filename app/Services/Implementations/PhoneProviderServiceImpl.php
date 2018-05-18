<?php
/**
 * Created by PhpStorm.
 * User: gitzj
 * Date: 3/29/2018
 * Time: 9:35 PM
 */

namespace App\Services\Implementations;

use App\Models\PhoneProvider;
use App\Models\PhonePrefix;

use App\Services\PhoneProviderService;

class PhoneProviderServiceImpl implements PhoneProviderService
{
    public function create(
        $name,
        $short_name,
        $status,
        $remarks,
        $prefixes
    )
    {
        $ph = new PhoneProvider();
        $ph->name = $name;
        $ph->short_name = $short_name;
        $ph->status = $status;
        $ph->remarks = $remarks;

        $ph->save();

        for ($i = 0; $i < count($prefixes); $i++) {
            $pp = new PhonePrefix();
            $pp->prefix = $prefixes[$i];

            $ph->prefixes()->save($pp);
        }
    }

    public function read()
    {
        return PhoneProvider::with('prefixes')->get();
    }

    public function update(
        $id,
        $name,
        $short_name,
        $status,
        $remarks,
        $prefixes
    )
    {
        $ph = PhoneProvider::find($id);

        if (!is_null($ph)) {
            $ph->name = $name;
            $ph->short_name = $short_name;
            $ph->status = $status;
            $ph->remarks = $remarks;

            $ph->save();
        }

        $ph->prefixes->each(function($pr) { $pr->delete(); });

        for ($i = 0; $i < count($prefixes); $i++) {
            $pp = new PhonePrefix();
            $pp->prefix = $prefixes[$i];

            $ph->prefixes()->save($pp);
        }
    }

    public function delete($id)
    {
        $ph = PhoneProvider::find($id);
        $ph->prefixes->each(function($ph) { $ph->delete(); });
        $ph->delete();
    }
}
<?php

namespace App\Http\Controllers;

use Lang;
use Config;
use Illuminate\Http\Request;

class LookupController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getLookupByCategory($category)
    {
        $result = array();

        foreach (Config::get('lookup.VALUE.'.strtoupper($category)) as $key) {
            array_push($result, array (
                'code' => $key,
                'description' => Lang::get('lookup.'.$key)
            ));
        }

        return $result;
    }

    public function getLookupI18nDescriptionByValue($value)
    {
        return Lang::get('lookup.'.$value);
    }
}

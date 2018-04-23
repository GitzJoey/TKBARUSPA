<?php

namespace App\Http\Controllers;

use Auth;
use Validator;
use App\Http\Requests;
use LaravelLocalization;
use Illuminate\Http\Request;
use Vinkla\Hashids\Facades\Hashids;
use Intervention\Image\Facades\Image;

use App\Services\ProductService;

class ProductController extends Controller
{
    private $productService;

    public function __construct(ProductService $productService)
    {
        $this->middleware('auth');
        $this->productService = $productService;
    }

    public function index(Request $req)
    {
        return view('product.index');
    }

    public function read(Request $request)
    {
        $productName = $request->query('p');

        if ($productName != '') {
            return $this->productService->read($productName);
        } else {
            return $this->productService->read();
        }
    }

    public function store(Request $request)
    {
        Validator::make($request->all(), [
            'type' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'status' => 'required|string|max:255',
        ])->validate();

        if (count($request['unit_id']) == 0) {
            $rules = ['unit' => 'required'];
            $messages = ['unit.required' =>
                LaravelLocalization::getCurrentLocale() == "en" ?
                    "Please provide at least 1 unit.":
                    "Harap isi paling tidak 1 satuan"];
            Validator::make($request->all(), $rules, $messages)->validate();
        }

        $isBaseFound = false;

        for ($i = 0; $i < count($request['is_base']); $i++) {
            if ($request['is_base'][$i]) { $isBaseFound = true; }
        }

        if (!$isBaseFound) {
            $rules = ['unit' => 'required'];
            $messages = ['unit.required' =>
                LaravelLocalization::getCurrentLocale() == "en" ?
                    "Please provide at least 1 base unit.":
                    "Harap isi paling tidak 1 satuan dasar."];
            Validator::make($request->all(), $rules, $messages)->validate();
        }

        $displayFound = false;

        for ($i = 0; $i < count($request['display']); $i++) {
            if ($request['display'][$i]) { $displayFound = true; }
        }

        if (!$displayFound) {
            $rules = ['unit' => 'required'];
            $messages = ['unit.required' =>
                LaravelLocalization::getCurrentLocale() == "en" ?
                    "Please provide at least 1 display unit.":
                    "Harap isi paling tidak 1 tampilan."];
            Validator::make($request->all(), $rules, $messages)->validate();
        }

        $productUnits = [];
        for ($i = 0; $i < count($request['unit_id']); $i++) {
            array_push($productUnits, array (
                'unit_id' => Hashids::decode($request['unit_id'][$i])[0],
                'is_base' => $request["is_base"][$i],
                'display' => $request["display"][$i],
                'conversion_value' => $request["conversion_value"][$i],
                'remarks' => $request["punit_remarks"][$i],
            ));
        }

        $productCategories = [];
        for ($i = 0; $i < count($request['cat_level']); $i++) {
            array_push($productCategories, array (
                'cat_level' => $request['cat_level'][$i],
                'cat_code' => $request["cat_code"][$i],
                'cat_name' => $request["cat_name"][$i],
                'cat_description' => $request["cat_description"][$i],
            ));
        }

        $this->productService->create(
            Auth::user()->company->id,
            Hashids::decode($request['type'])[0],
            $productCategories,
            $request['name'],
            $request['image_filename'],
            $request['short_code'],
            $request['barcode'],
            $productUnits,
            $request['minimal_in_stock'],
            $request['description'],
            $request['status'],
            $request['remarks']
        );

        return response()->json();
    }

    public function update($id, Request $request)
    {
        Validator::make($request->all(), [
            'type' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'status' => 'required|string|max:255',
        ])->validate();

        if (count($request['unit_id']) == 0) {
            $rules = ['unit' => 'required'];
            $messages = ['unit.required' =>
                LaravelLocalization::getCurrentLocale() == "en" ?
                    "Please provide at least 1 unit.":
                    "Harap isi paling tidak 1 satuan"];
            Validator::make($request->all(), $rules, $messages)->validate();
        }

        $isBaseFound = false;

        for ($i = 0; $i < count($request['is_base']); $i++) {
            if ($request['is_base'][$i]) { $isBaseFound = true; }
        }

        if (!$isBaseFound) {
            $rules = ['unit' => 'required'];
            $messages = ['unit.required' =>
                LaravelLocalization::getCurrentLocale() == "en" ?
                    "Please provide at least 1 base unit.":
                    "Harap isi paling tidak 1 satuan dasar."];
            Validator::make($request->all(), $rules, $messages)->validate();
        }

        $displayFound = false;

        for ($i = 0; $i < count($request['display']); $i++) {
            if ($request['display'][$i]) { $displayFound = true; }
        }

        if (!$displayFound) {
            $rules = ['unit' => 'required'];
            $messages = ['unit.required' =>
                LaravelLocalization::getCurrentLocale() == "en" ?
                    "Please provide at least 1 display unit.":
                    "Harap isi paling tidak 1 tampilan."];
            Validator::make($request->all(), $rules, $messages)->validate();
        }

        $productUnits = [];
        for ($i = 0; $i < count($request['unit_id']); $i++) {
            array_push($productUnits, array (
                'unit_id' => Hashids::decode($request['unit_id'][$i])[0],
                'is_base' => $request["is_base"][$i],
                'display' => $request["display"][$i],
                'conversion_value' => $request["conversion_value"][$i],
                'remarks' => $request["punit_remarks"][$i],
            ));
        }

        $productCategories = [];
        for ($i = 0; $i < count($request['cat_level']); $i++) {
            array_push($productCategories, array (
                'cat_level' => Hashids::decode($request['cat_level'][$i])[0],
                'cat_code' => $request["cat_code"][$i],
                'cat_name' => $request["cat_name"][$i],
                'cat_description' => $request["cat_description"][$i],
            ));
        }

        $this->productService->update(
            $id,
            Auth::user()->company->id,
            $request['type'],
            $productCategories,
            $request['name'],
            $request['image_filename'],
            $request['short_code'],
            $request['barcode'],
            $productUnits,
            $request['minimal_in_stock'],
            $request['description'],
            $request['status'],
            $request['remarks']
        );

        return response()->json();
    }

    public function delete($id)
    {
        $this->productService->delete($id);

        return response()->json();
    }
}

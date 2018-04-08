<?php

namespace App\Http\Controllers;

use Auth;
use Exception;
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

        if ($productName == '') {
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

        $productUnits = [];
        for ($i = 0; $i < count($request['unit_id']); $i++) {
            array_push($productUnits, array (
                'unit_id' => Hashids::decode($request[$i]['unit_id'])[0],
                'is_base' => $request[$i]["is_base"],
                'conversion_value' => $request[$i]["conversion_value"],
                'remarks' => $request[$i]["punit_remarks"],
            ));
        }

        $productCategories = [];
        for ($i = 0; $i < count($request['cat_level']); $i++) {
            array_push($productCategories, array (
                'cat_level' => Hashids::decode($request[$i]['cat_level'])[0],
                'cat_code' => $request[$i]["cat_code"],
                'cat_name' => $request[$i]["cat_name"],
                'cat_description' => $request[$i]["cat_description"],
            ));
        }

        $this->productService->create(
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

        $productUnits = [];
        for ($i = 0; $i < count($request['unit_id']); $i++) {
            array_push($productUnits, array (
                'unit_id' => Hashids::decode($request[$i]['unit_id'])[0],
                'is_base' => $request[$i]["is_base"],
                'conversion_value' => $request[$i]["conversion_value"],
                'remarks' => $request[$i]["punit_remarks"],
            ));
        }

        $productCategories = [];
        for ($i = 0; $i < count($request['cat_level']); $i++) {
            array_push($productCategories, array (
                'cat_level' => Hashids::decode($request[$i]['cat_level'])[0],
                'cat_code' => $request[$i]["cat_code"],
                'cat_name' => $request[$i]["cat_name"],
                'cat_description' => $request[$i]["cat_description"],
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
        $product = Product::find($id);

        $product->productUnits->each(function($pu) { $pu->delete(); });
        $product->productCategories->each(function($pc) { $pc->delete(); });
        $product->delete();

        return redirect(route('db.master.product'));
    }
}

<?php

namespace App\Http\Controllers;

use Validator;
use LaravelLocalization;
use Illuminate\Http\Request;

use App\Services\CompanyService;

class CompanyController extends Controller
{
    private $companyService;

    public function __construct(CompanyService $companyService)
    {
        $this->middleware('auth');
        $this->companyService = $companyService;
    }

    public function index()
    {
        return view('company.index');
    }

    public function readAll(Request $request)
    {
        $limit = $request->query('l');

        if (empty($limit)) {
            return $this->companyService->readAll();
        } else {
            return $this->companyService->readAll($limit);
        }
    }

    public function store(Request $request)
    {
        Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'tax_id' => 'required|string|max:255',
            'status' => 'required',
            'is_default' => 'required',
            'frontweb' => 'required',
            'image_path' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ])->validate();

        $bank = [];

        $this->companyService->create(
            $request->name,
            $request->address,
            $request->latitude,
            $request->longitude,
            $request->phone_num,
            $request->fax_num,
            $request->tax_id,
            $request->status,
            $request->is_default,
            $request->frontweb,
            $request->image_path,
            $request->remarks,
            $bank,
            $request->date_format,
            $request->time_format,
            $request->thousand_separator,
            $request->decimal_separator,
            $request->decimal_digit,
            $request->ribbon
        );

        return response()->json();
    }

    public function edit($id, Request $request)
    {

    }

    public function delete($id)
    {
        Validator::extend('isdefault', function ($field, $value, $parameters) {
            return $value == 'YESNOSELECT.YES' ? false : true;
        });

        $inputs = array(
            'is_default' => $company->is_default
        );

        $rules = array('is_default' => 'isdefault');

        $messages = array(
            'isdefault' => LaravelLocalization::getCurrentLocale() == 'en' ? 'Default Company cannot be deleted':'Toko Utama tidak bisa di hapus'
        );

        $validator = Validator::make($inputs, $rules, $messages);

        if ($validator->fails()) {
            return redirect(route('db.admin.store'))->withErrors($validator);
        } else {
            $company->delete();
        }

        return redirect(route('db.admin.store'));
    }
}

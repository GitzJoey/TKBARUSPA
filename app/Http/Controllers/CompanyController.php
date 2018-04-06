<?php

namespace App\Http\Controllers;

use Validator;
use LaravelLocalization;
use Illuminate\Http\Request;
use Vinkla\Hashids\Facades\Hashids;

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

        for ($i = 0; $i < count($request['bank_id']); $i++) {
            array_push($bank, array (
                'bank_id' => Hashids::decode($request['bank_id'][$i])[0],
                'account_name' => $request["account_name"][$i],
                'account_number' => $request["account_number"][$i],
                'bank_remarks' => $request["bank_remarks"][$i],
            ));
        }

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

    public function update($id, Request $request)
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

        for ($i = 0; $i < count($request['bank_id']); $i++) {
            array_push($bank, array (
                'bank_id' => Hashids::decode($request['bank_id'][$i])[0],
                'account_name' => $request["account_name"][$i],
                'account_number' => $request["account_number"][$i],
                'bank_remarks' => $request["bank_remarks"][$i],
            ));
        }

        $this->companyService->update(
            $id,
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

    public function delete($id)
    {
        $this->companyService->delete($id);

        return response()->json();
    }
}

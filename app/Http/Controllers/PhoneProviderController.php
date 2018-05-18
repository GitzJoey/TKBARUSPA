<?php

namespace App\Http\Controllers;

use DB;
use Exception;
use Validator;
use Illuminate\Http\Request;

use App\Services\PhoneProviderService;

class PhoneProviderController extends Controller
{
    private $phoneProviderService;

    public function __construct(PhoneProviderService $phoneProviderService)
    {
        $this->middleware('auth');
        $this->phoneProviderService = $phoneProviderService;
    }

    public function index() 
    {
        return view('phone_provider.index');
    }

    public function read()
    {
        return $this->phoneProviderService->read();
    }

    public function store(Request $req) 
    {
        Validator::make($req->all(), [
            'name' => 'required|string|max:255',
            'short_name' => 'required|string|max:255',
            'status' => 'required',
        ])->validate();

        DB::beginTransaction();
        try {
            $this->phoneProviderService->create($req['name'], $req['short_name'], $req['status'], $req['remarks'], $req['prefixes']);

            DB::commit();
            return response()->json();
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function update(Request $req, $id) 
    {
        Validator::make($req->all(), [
            'name' => 'required|string|max:255',
            'short_name' => 'required|string|max:255',
            'status' => 'required',
        ])->validate();

        DB::beginTransaction();
        try {
            $this->phoneProviderService->update($id, $req['name'], $req['short_name'], $req['status'], $req['remarks'], $req['prefixes']);

            DB::commit();
            return response()->json();
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function delete($id) 
    {
        DB::beginTransaction();
        try {
            $this->phoneProviderService->delete($id);

            DB::commit();
            return response()->json();
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

}

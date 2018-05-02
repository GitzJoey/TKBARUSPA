<?php

namespace App\Http\Controllers;

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

        $this->phoneProviderService->create($req['name'], $req['short_name'], $req['status'], $req['remarks'], $req['prefixes']);

        return response()->json();
    }

    public function update(Request $req, $id) 
    {
        Validator::make($req->all(), [
            'name' => 'required|string|max:255',
            'short_name' => 'required|string|max:255',
            'status' => 'required',
        ])->validate();

        $this->phoneProviderService->update($id, $req['name'], $req['short_name'], $req['status'], $req['remarks'], $req['prefixes']);

        return response()->json($req->all());
    }

    public function delete($id) 
    {
        $this->phoneProviderService->delete($id);

        return response()->json();
    }

}

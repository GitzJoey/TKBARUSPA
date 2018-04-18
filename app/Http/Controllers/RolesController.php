<?php
/**
 * Created by PhpStorm.
 * User: GitzJoey
 * Date: 9/5/2016
 * Time: 10:47 PM
 */

namespace App\Http\Controllers;

use Validator;
use Illuminate\Http\Request;

use App\Services\RolesService;

class RolesController extends Controller
{
    private $rolesService;

    public function __construct(RolesService $rolesService)
    {
        $this->middleware('auth');
        $this->rolesService = $rolesService;
    }

    public function index()
    {
        return view('role.index');
    }

    public function read()
    {
        return $this->rolesService->read();
    }

    public function store(Request $request)
    {
        Validator::make($request->all(), [
            'name' => 'required|max:255',
            'display_name' => 'required|max:255',
            'description' => 'required',
        ])->validate();

        $this->rolesService->create(
            $request['name'],
            $request['display_name'],
            $request['description'],
            $request['permission']
        );

        return response()->json();
    }


    public function update($id, Request $request)
    {
        Validator::make($request->all(), [
            'name' => 'required|max:255',
            'display_name' => 'required|max:255',
            'description' => 'required',
        ])->validate();

        $this->rolesService->update(
            $id,
            $request['name'],
            $request['display_name'],
            $request['description'],
            $request['permission']
        );

        return response()->json();
    }

    public function delete($id)
    {
        $this->rolesService->delete($id);

        return response()->json();
    }
}

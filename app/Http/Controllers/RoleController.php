<?php
/**
 * Created by PhpStorm.
 * User: GitzJoey
 * Date: 9/5/2016
 * Time: 10:47 PM
 */

namespace App\Http\Controllers;

use DB;
use Exception;
use Validator;
use Illuminate\Http\Request;

use App\Services\RoleService;

class RoleController extends Controller
{
    private $roleService;

    public function __construct(RoleService $roleService)
    {
        $this->middleware('auth');
        $this->roleService = $roleService;
    }

    public function index()
    {
        return view('role.index');
    }

    public function read()
    {
        return $this->roleService->read();
    }

    public function store(Request $request)
    {
        Validator::make($request->all(), [
            'name' => 'required|max:255',
            'display_name' => 'required|max:255',
            'description' => 'required',
        ])->validate();

        DB::beginTransaction();
        try {
            $rolePermission = [];
            for($i = 0; $i < count($request['permission']); $i++) {
                array_push($rolePermission, array (
                    'id' => $request['permission'][$i]
                ));
            }

            $this->roleService->create(
                $request['name'],
                $request['display_name'],
                $request['description'],
                $rolePermission
            );

            DB::commit();
            return response()->json();
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function update($id, Request $request)
    {
        Validator::make($request->all(), [
            'name' => 'required|max:255',
            'display_name' => 'required|max:255',
            'description' => 'required',
        ])->validate();

        DB::beginTransaction();
        try {
            $rolePermission = [];
            $inputtedRolePermission = [];
            for($i = 0; $i < count($request['permission']); $i++) {
                array_push($rolePermission, array (
                    'id' => $request['permission'][$i]
                ));
                array_push($inputtedRolePermission, $request['permission'][$i]);
            }

            $this->roleService->update(
                $id,
                $request['name'],
                $request['display_name'],
                $request['description'],
                $rolePermission,
                $inputtedRolePermission
            );

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
            $this->roleService->delete($id);

            DB::commit();
            return response()->json();
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function getAllPermissions()
    {
        return $this->roleService->getAllPermissions();
    }

}

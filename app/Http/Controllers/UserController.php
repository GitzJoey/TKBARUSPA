<?php
/**
 * Created by PhpStorm.
 * User: GitzJoey
 * Date: 9/5/2016
 * Time: 10:40 PM
 */

namespace App\Http\Controllers;

use Auth;
use Validator;
use Illuminate\Http\Request;
use Vinkla\Hashids\Facades\Hashids;

use App\Services\UserService;

class UserController extends Controller
{
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->middleware('auth');
        $this->userService = $userService;
    }

    public function index()
    {
        return view('user.index');
    }

    public function read()
    {
        return $this->userService->read();
    }

    public function profile($id)
    {
        $user = '';
        return view('user.profile', compact('user'));
    }

    public function store(Request $request)
    {
        Validator::make($request->all(), [
            'first_name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
            'roles' => 'required',
            'company' => 'required',
        ])->validate();

        $name = trim($request['first_name'] . ' ' . $request['last_name'], " ");
        $profile = [];
        $pic_phone = [];

        for ($j = 0; $j < count($request['phone_provider_id']); $j++) {
            array_push($pic_phone, array(
                'phone_provider_id' => Hashids::decode($request['phone_provider_id'][$j])[0],
                'number' => $request['phone_number'][$j],
                'remarks' => $request['remarks'][$j]
            ));
        }

        array_push($profile, array (
            'first_name' => $request['first_name'],
            'last_name' => $request['last_name'],
            'address' => $request['address'],
            'ic_num' => $request['ic_num'],
            'phone_numbers' => $pic_phone
        ));

        $rolesId = Hashids::decode($request['roles'])[0];

        $this->userService->create(
            $name,
            $request['email'],
            $request['password'],
            $rolesId,
            Auth::user()->company->id,
            $request['active'],
            $profile
        );

        return response()->json();
    }

    public function update($id, Request $request)
    {
        Validator::make($request->all(), [
            'first_name' => 'required|max:255',
            'email' => 'required|email|max:255',
            'roles' => 'required',
            'company' => 'required',
        ])->validate();

        if (!empty($request['password'])) {
            Validator::make($request->all(), [
                'password' => 'required|min:6|confirmed',
            ])->validate();
        }

        $name = trim($request['first_name'] . ' ' . $request['last_name'], " ");
        $profile = [];
        $pic_phone = [];

        for ($j = 0; $j < count($request['phone_provider_id']); $j++) {
            array_push($pic_phone, array(
                'phone_provider_id' => Hashids::decode($request['phone_provider_id'][$j])[0],
                'number' => $request['phone_number'][$j],
                'remarks' => $request['remarks'][$j]
            ));
        }

        array_push($profile, array (
            'first_name' => $request['first_name'],
            'last_name' => $request['last_name'],
            'address' => $request['address'],
            'ic_num' => $request['ic_num'],
            'phone_numbers' => $pic_phone
        ));

        $rolesId = Hashids::decode($request['roles'])[0];

        $this->userService->update(
            $id,
            $name,
            $request['email'],
            $request['password'],
            $rolesId,
            $request['active'],
            Auth::user()->company->id,
            $profile
        );

        return response()->json();
    }

    public function delete($id)
    {
        $this->userService->delete($id);

        return response()->json();
    }
}

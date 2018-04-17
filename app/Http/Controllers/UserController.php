<?php
/**
 * Created by PhpStorm.
 * User: GitzJoey
 * Date: 9/5/2016
 * Time: 10:40 PM
 */

namespace App\Http\Controllers;

use Validator;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\User;
use App\Models\Role;

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
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
            'roles' => 'required',
            'company' => 'required',
        ])->validate();

        $name = '';
        $profile = [];

        $pic_phone = [];
        for ($j = 0; $j < count($request['profile_' . $i . '_phone_provider']); $j++) {
            array_push($pic_phone, array(
                'phone_provider_id' => Hashids::decode($request['profile_' . $i . '_phone_provider'][$j])[0],
                'number' => $request['profile_' . $i . '_phone_number'][$j],
                'remarks' => $request['profile_' . $i . '_remarks'][$j]
            ));
        }

        array_push($persons_in_charge, array (
            'first_name' => $request['first_name'][$i],
            'last_name' => $request['last_name'][$i],
            'address' => $request['profile_address'][$i],
            'ic_num' => $request['ic_num'][$i],
            'phone_numbers' => $pic_phone
        ));


        $this->userService->create(
            $request['name'],
            $request['email'],
            $request['password'],
            $request['roles'],
            Auth::user()->company->id,
            $profile
        );

        return response()->json();
    }

    public function update($id, Request $request)
    {
        Validator::make($request->all(), [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
            'roles' => 'required',
            'company' => 'required',
        ])->validate();

        $name = '';
        $profile = [];


        return response()->json();
    }

    public function delete($id)
    {
        $this->userService->delete($id);

        return response()->json();
    }
}

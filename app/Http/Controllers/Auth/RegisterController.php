<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Models\Profile;

use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        $usr = new User;
        $usr->name = $data['name'];
        $usr->email = $data['email'];
        $usr->password = bcrypt($data['password']);

        if (env('MAIL_USER_ACTIVATION', false)) {
            $usr->active = false;
            $usr->email_activation_token = str_random(60);
        } else {
            $usr->active = true;
        }

        $usr->created_at = Carbon::now();
        $usr->updated_at = Carbon::now();

        /*
        if (!empty($data['company_name'])) {
            $id = $this->companyService->createDefaultCompany($data['company_name']);
            $usr->company_id = $id;
        } else if (!empty($data['company_id'])) {
            $usr->company_id = $data['company_id'];
        } else if (!empty($data['picked_company_id'])) {
            $this->companyService->setDefaultCompany($data['picked_company_id']);
            $usr->company_id = $data['picked_company_id'];
        } else {

        }
        */
        $usr->save();

        $profile = new Profile();
        if ($data['name'] == trim($data['name']) && strpos($data['name'], ' ') !== false) {
            $pieces = explode(" ", $data['name']);
            $profile->first_name = $pieces[0];
            $profile->last_name = $pieces[1];
        } else {
            $profile->first_name = $data['name'];
        }

        $usr->profile()->save($profile);

        if (!empty($data['company_name'])) {
            $usr->attachRole('administrator');
        } else {
            $usr->attachRole('user');
        }

        return $usr;
    }

    public function showRegistrationForm()
    {
        return view('auth.codebase.register');
    }
}

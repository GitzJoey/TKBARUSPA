<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Models\Profile;

use App\Services\CompanyService;

use App\Events\Auth\UserActivationEmail;

use Lang;
use Session;
use Validator;
use Carbon\Carbon;
use LaravelLocalization;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
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

    private $companyService;
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
    public function __construct(CompanyService $companyService)
    {
        $this->middleware('guest');
        $this->companyService = $companyService;
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $niceNames = [
            'company_name' => Lang::getLocale() == 'en' ? 'Company Name':'Nama Perusahaan',
            'name' => Lang::getLocale() == 'en' ? 'Name':'Nama Lengkap',
            'email' => Lang::getLocale() == 'en'? 'Email':'Email',
            'password' => Lang::getLocale() == 'en'? 'Password':'Password',
            'terms' => Lang::getLocale() == 'en' ? 'Terms & Conditions':'Syarat & Ketentuan',
        ];

        if (array_key_exists('company_name', $data)) {
            return Validator::make($data, [
                'company_name' => 'required',
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:6|confirmed',
                'terms' => 'required',
            ], Lang::get('validation'), $niceNames);
        }

        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'terms' => 'required',
        ], Lang::get('validation'), $niceNames);
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

    /**
     * Override the RegistersUsers@showRegistrationForm
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showRegistrationForm(Request $req)
    {
        $companyDDL = $this->companyService->read();
        $company_id = 0;
        $company_name = '';

        if (!empty($req->query('mode'))) {
            if ($req->query('mode') == 'create') {
                $company_mode = 'create';
            } else if ($req->query('mode') == 'pick' && !$this->companyService->isEmptyCompanyTable()) {
                $company_mode = 'pick';
            } else {
                if ($this->companyService->isEmptyCompanyTable()) {
                    $company_mode = 'create';
                } else if ($this->companyService->defaultStorePresent()) {
                    $company_mode = 'use_default';
                    $company_id = $this->companyService->getDefaultCompany()->id;
                    $company_name = $this->companyService->getDefaultCompany()->name;
                } else {
                    $company_mode = 'pick';
                }
            }
        } else {
            if ($this->companyService->isEmptyCompanyTable()) {
                $company_mode = 'create';
            } else if ($this->companyService->defaultStorePresent()) {
                $company_mode = 'use_default';
                $company_id = $this->companyService->getDefaultCompany()->id;
                $company_name = $this->companyService->getDefaultCompany()->name;
            } else {
                $company_mode = 'pick';
            }
        }

        return view('auth.codebase.register', compact('company_mode', 'companyDDL', 'company_id', 'company_name'));
    }

    protected function registered(Request $request, $user)
    {
        if (env('MAIL_USER_ACTIVATION', false)) {
            event(new UserActivationEmail($user));

            $this->guard()->logout();

            return redirect()->route('login')->withSuccess(
                LaravelLocalization::getCurrentLocale() == 'id' ?
                    'Harap Cek Email Untuk Aktivasi':
                    'Please Check Your Email For Activation'
            );
        }
    }

    protected function activate(Request $request, $token)
    {
        $usr = User::where('email_activation_token', '=', $token)->first();

        if (count($usr) > 0) {
            $usr->active = true;
            $usr->save();

            Session::flash('success', LaravelLocalization::getCurrentLocale() == 'id' ?
                'Akun Anda Sudah Diaktifkan':
                'Your Account Successfully Activated.');

            return view('auth.codebase.login');
        } else {
            Session::flash('error', LaravelLocalization::getCurrentLocale() == 'id' ?
                'Kode Aktivasi Salah Atau Tidak Ditemukan':
                'Activation Code Is Invalid Or Not Found');

            return view('auth.codebase.passwords.activate');
        }
    }

    protected function activateResend(Request $request)
    {
        $usr = User::whereEmail($request->email)->first();

        if (count($usr) > 0) event(new UserActivationEmail($usr));

        return view('auth.codebase.login');
    }
}

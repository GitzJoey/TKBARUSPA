<?php

namespace App\Http\Controllers\Auth;

use App\User;

use App\Services\DatabaseService;

use Lang;
use Validator;
use LaravelLocalization;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    private $databaseService;
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(DatabaseService $databaseService)
    {
        $this->middleware('guest')->except('logout');
        $this->databaseService = $databaseService;
    }

    public function showLoginForm()
    {
        if (!$this->databaseService->isOnline()) {
            return "Database Is Not Online";
        }

        if (isset($_SERVER['HTTP_USER_AGENT']) &&
            (strpos($_SERVER['HTTP_USER_AGENT'], 'Trident/') !== false)) {
            return "Internet Explorer Browser Is Not Supported";
        }

        return view('auth.codebase.login');
    }

    protected function validateLogin(Request $request)
    {
        $niceNames = [
            'email' => Lang::getLocale() == 'en'? 'Email':'Email',
            'password' => Lang::getLocale() == 'en'? 'Password':'Password',
        ];

        Validator::extend('is_allowed_login', function($attribute, $value, $parameters, $validator) {
            return true;
        });

        Validator::extend('is_activated', function($attribute, $value, $parameters, $validator) {
            $usr = User::where('email', '=', $value);

            if (count($usr->first()) == 0) return true;

            if (!env('MAIL_USER_ACTIVATION', false)) return true;

            if ($usr->first()->active) return true;
            else return false;
        });

        $this->validate($request, [
            $this->username() => 'required|string|is_allowed_login|is_activated',
            'password' => 'required|string',
        ], [
            $this->username().'.is_allowed_login' => LaravelLocalization::getCurrentLocale() == 'en' ? 'Login Not Allowed':'Tidak Diperkenankan Login',
            $this->username().'.is_activated' => LaravelLocalization::getCurrentLocale() == 'en' ? 'Email Has Not Been Activated':'Email Belum Di Aktivasi'
        ], $niceNames);
    }
}

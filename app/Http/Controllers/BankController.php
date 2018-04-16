<?php
/**
 * Created by PhpStorm.
 * User: GitzJoey
 * Date: 9/7/2016
 * Time: 12:33 AM
 */

namespace App\Http\Controllers;

use Config;
use Validator;
use Illuminate\Http\Request;

use App\Services\BankService;

class BankController extends Controller
{
    private $bankService;

    public function __construct(BankService $bankService)
    {
        $this->middleware('auth');
        $this->bankService = $bankService;
    }

    public function index()
    {
        return view('bank.index');
    }

    public function read()
    {
        return $this->bankService->read();
    }
}

<?php

namespace App\Http\Controllers;

use Lang;
use Config;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
}

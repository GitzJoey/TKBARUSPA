<?php

namespace App\Http\Controllers;

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

    public function read()
    {
        return $this->phoneProviderService->read();
    }
}

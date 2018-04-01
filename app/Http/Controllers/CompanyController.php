<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Services\CompanyService;

class CompanyController extends Controller
{
    private $companyService;

    public function __construct(CompanyService $companyService)
    {
        $this->middleware('auth', [
            'except' => [
                'readAll',
            ]
        ]);
        $this->companyService = $companyService;
    }

    public function index()
    {
        return view('company.index');
    }

    public function readAll(Request $request)
    {
        $limit = $request->query('l');
        try {
            if (empty($limit)) {
                return $this->companyService->readAll();
            } else {
                return $this->companyService->readAll($limit);
            }
        } catch (Exception $e) {
            return response()->json($e);
        }
    }
}

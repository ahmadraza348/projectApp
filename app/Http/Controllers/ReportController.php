<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ReportService;


class ReportController extends Controller
{
    public $service;

    public function __construct(ReportService $service)
    {
        $this->service = $service;
    }


    public function index()
    {
       $data = $this->service->fetchData();
        return view('reports', compact('data'));
    }
}

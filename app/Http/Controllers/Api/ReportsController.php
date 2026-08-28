<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ReportService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class ReportsController extends Controller
{
    use ApiResponse;
    public function __construct(protected ReportService $service) {}

    public function index(): JsonResponse
    {
        $data = $this->service->fetchData();

        return $this->successResponse([  
            $data,         
            'Reports Fetched Successfully',
        ], 200);
    }
}

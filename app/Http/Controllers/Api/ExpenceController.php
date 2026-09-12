<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ExpenceRequest;
use App\Http\Resources\ExpenceResource;
use App\Models\Expence;
use App\Services\ExpenceService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class ExpenceController extends Controller
{
    use ApiResponse;

    public function __construct(private ExpenceService $service) {}

    public function index(): JsonResponse
    {
        return $this->successResponse(ExpenceResource::collection($this->service->fetchData())->response()->getData(true), 'Expenses fetched successfully');
    }

    public function store(ExpenceRequest $request): JsonResponse
    {
        return $this->successResponse(new ExpenceResource($this->service->store($request->validated())), 'Expense created successfully', 201);
    }

    public function show(Expence $expence): JsonResponse
    {
        return $this->successResponse(new ExpenceResource($expence->load('project')));
    }

    public function update(ExpenceRequest $request, Expence $expence): JsonResponse
    {
        return $this->successResponse(new ExpenceResource($this->service->update($expence, $request->validated())->load('project')), 'Expense updated successfully');
    }

    public function destroy(Expence $expence): JsonResponse
    {
        $this->service->destroy($expence);
        return $this->successResponse(null, 'Expense deleted successfully');
    }
}

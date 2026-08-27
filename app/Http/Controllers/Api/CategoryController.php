<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Services\CategoryService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    use ApiResponse;

    public function __construct(protected CategoryService $service) {}

    public function index(): JsonResponse
    {
        $categories = $this->service->fetchData();
        return response()->json(CategoryResource::collection($categories)->response()->getData(true));
    }

    public function show(Category $category): JsonResponse
    {
        return $this->successResponse(new CategoryResource($category->loadCount('projects')));
    }

    public function store(CategoryRequest $request): JsonResponse
    {
        $category = $this->service->store($request->validated());
        return $this->successResponse(new CategoryResource($category), 'Category created.', 201);
    }

    public function update(CategoryRequest $request, Category $category): JsonResponse
    {
        $category = $this->service->update($category, $request->validated());
        return $this->successResponse(new CategoryResource($category), 'Category updated.');
    }

    public function destroy(Category $category): JsonResponse
    {
        $this->service->destroy($category);
        return $this->successResponse(null, 'Category deleted.');
    }
}

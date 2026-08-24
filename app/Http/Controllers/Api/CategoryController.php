<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Services\CategoryService;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    public function __construct(protected CategoryService $service)
    {
    }

    public function index(): JsonResponse
    {
        $categories = $this->service->fetchData();

        return response()->json([
            'success' => true,
            'data' => CategoryResource::collection($categories),
            'meta' => [
                'current_page' => $categories->currentPage(),
                'last_page' => $categories->lastPage(),
                'per_page' => $categories->perPage(),
                'total' => $categories->total(),
            ],
        ], 200); 
    }


    public function show(Category $category): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => new CategoryResource($category->loadCount('projects')),
        ], 200);
    }

    public function store(CategoryRequest $request): JsonResponse
    {
        $category = $this->service->store($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Category created.',
            'data' => new CategoryResource($category),
        ], 201); 
    }

    public function update(CategoryRequest $request, Category $category): JsonResponse
    {
        $category = $this->service->update($category, $request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Category updated.',
            'data' => new CategoryResource($category),
        ], 200);
    }

    public function destroy(Category $category): JsonResponse
    {
        $this->service->destroy($category);

        return response()->json([
            'success' => true,
            'message' => 'Category deleted.',
        ], 200);     
    }
}

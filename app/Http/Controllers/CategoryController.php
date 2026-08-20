<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Services\CategoryService;
use App\Models\Category;

class CategoryController extends Controller
{
    public $service;

    public function __construct(CategoryService $service)
    {
        $this->service = $service;
    }

    public function index(){
        $categories = $this->service->fetchData();
        return view('category', compact('categories'));
    }

    public function store(CategoryRequest $request){
        $this->service->store($request->validated());
        return back()->with('success', 'Category Added');
    }

    public function update(CategoryRequest $request, Category $category){
        $category = $this->service->update($category, $request->validated());
        return back()->with('success', 'Category Updated');
    }

    public function destroy(Category $category){
       $this->service->destroy($category);
        return back()->with('success', 'Category Deleted');
    }
}


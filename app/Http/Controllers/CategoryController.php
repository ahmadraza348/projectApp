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
        toastr()->success('Category Added');
        return redirect()->back();
    }

    public function update(CategoryRequest $request, Category $category){
        $category = $this->service->update($category, $request->validated());
        toastr()->success('Category Updated');
        return redirect()->back();
    }

    public function destroy(Category $category){
       $this->service->destroy($category);
        toastr()->success('Category Deleted');
        return redirect()->back();
    }
}


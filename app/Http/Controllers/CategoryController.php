<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CategoryRequest;
use App\Services\CategoryService;

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
}

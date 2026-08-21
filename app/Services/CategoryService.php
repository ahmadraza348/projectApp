<?php

namespace App\Services;
use App\Models\Category;

class CategoryService
{
    public function fetchData() 
    {
    return Category::paginate(10);
    }
   public function store(array $data): Category
   {
    return Category::create($data);     
   }

}


<?php

namespace App\Services;

use App\Models\Category;
use App\Models\User;

class ProjectService
{
    public function getCreateFormData(){
        $data['category'] = Category::where('status', true)->get();
        $data['users'] = User::all();
        return $data;
    }
}

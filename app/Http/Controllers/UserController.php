<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use App\Http\Requests\UserRequest;

class UserController extends Controller
{
    public $service;

    public function __construct(UserService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return view('user');
    }

    public function submit(UserRequest $request)
    {
         $this->service->submitUser($request->validated());
         return redirect()->route('user.index')->with('success', 'User created successfully.');
    }

    public function profile()
    {
       $user = $this->service->getProfileData();
        return view('profile', compact('user'));
    }
}

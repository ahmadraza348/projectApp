<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Services\AuthService;

class AuthController extends Controller
{     
    public $service;
    public function __construct(AuthService $authService)
    {
        $this->service = $authService;
    } 

    public function login()
    {
        return view('login');
    }

    public function authenticate(LoginRequest $LoginRequest)
    {
      return $this->service->authenticate($LoginRequest);      
    }

    public function logout(){       
        return $this->service->logout();
    }
}

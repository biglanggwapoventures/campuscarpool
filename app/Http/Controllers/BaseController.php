<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Dingo\Api\Routing\Helpers;
use JWTAuth;

class BaseController extends Controller
{
    use Helpers; 
    
    protected $user;

    public function __construct()
    {
        $this->user = JWTAuth::parseToken()->authenticate();
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use JWTAuth;

class RefreshTokenController extends Controller
{
    public function __invoke()
    {
        // $token = JWTAuth::refresh(JWTAuth::getToken());
        // return $this->response->noContent()->withHeader('Authorization', "Bearer {$token}");
        return $this->response->noContent();
    }
}

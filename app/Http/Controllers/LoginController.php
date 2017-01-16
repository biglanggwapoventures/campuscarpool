<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class LoginController extends Controller
{

    public function __invoke(Request $request)
    {
         // grab credentials from the request
        $credentials = $request->only('id_number', 'password');

        try {
            // attempt to verify the credentials and create a token for the user
            if (! $token = JWTAuth::attempt($credentials)) {
               return $this->response->errorForbidden('Invalid credentials');
            }
        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            return $this->response->errorInternal('Error encoding token');
        }

        // all good so return the token
        return $this->response->noContent()->withHeader('Authorization', "Bearer {$token}");
    }
}

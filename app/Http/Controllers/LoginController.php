<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Validator;
use App\User;

class LoginController extends Controller
{

    public function __invoke(Request $request)
    {
        $v = Validator::make($request->all(), [
            'id_number' => 'required|exists:users',
            'password' => 'required'
        ], [
            'id_number.exists' => 'Your id number is not yet registered.'
        ]);

        if($v->fails()){
            throw new \Dingo\Api\Exception\StoreResourceFailedException('Validation errors..', $v->errors());
        }

         // grab credentials from the request
        $credentials = $request->only('id_number', 'password');

        try {
            // attempt to verify the credentials and create a token for the user
            if (! $token = JWTAuth::attempt($credentials)) {
               throw new \Dingo\Api\Exception\StoreResourceFailedException('Validation errors..', ['password' => ['Incorrect password.']]);
            }
        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            return $this->response->errorInternal('Error encoding token');
        }

        $user = User::where(['id_number' => $request->id_number])->first();

        if($user->banned_at){
            throw new \Dingo\Api\Exception\StoreResourceFailedException('Validation errors..', ['id_number' => ['Your account has been banned! You are no longer be able to login!']]);
        }

        $user->display_photo = asset($user->display_photo);

        // all good so return the token
        return $this->response->array([
            'user' => $user,
            'jwt' => $token
        ])->withHeader('Authorization', "Bearer {$token}");
    }
}

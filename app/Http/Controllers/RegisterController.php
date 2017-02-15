<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\User;
use Illuminate\Support\Facades\Hash;
use JWTAuth;

class RegisterController extends Controller
{
    public function __invoke(Request $request)
    {

        $v = Validator::make($request->all(), [
            // 'display_photo' => 'required|image|max:2048',
            'role' => 'required|in:DRIVER,COMMUTER',
            'firstname' => 'required|max:255',
            'lastname' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'id_number' => 'required|max:255|unique:users,id_number',
            'password' => 'required|min:6|confirmed',
        ]);

        if($v->fails()){
            throw new \Dingo\Api\Exception\StoreResourceFailedException('Could not create new user.', $v->errors());
        }
        
        $data = $request->only([
            'role',
            'firstname',
            'lastname',
            'email',
            'id_number',
        ]);
        
        $data['password']  = Hash::make($request->password);

        $user = User::create($data);

        if($user->id){
            // $user->display_photo = $request->file('display_photo')->store("display_photos/{$user->id}", 'public');
            // $user->save();

            return $this->response->array([
                'user' => $user,
                'token' => JWTAuth::fromUser($user)
            ]);
        }
        
        return $this->response->errorInternal('Could not register a new user. Please try again later');
    }
}

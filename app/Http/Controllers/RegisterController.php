<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\User;

class RegisterController extends Controller
{
    public function __invoke(Request $request)
    {
        $data = $request->all();

        $v = Validator::make($data, [
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

        $user = User::create([
            'role' => $data['role'],
            'firstname' => $data['firstname'],
            'lastname' => $data['lastname'],
            'email' => $data['email'],
            'id_number' => $data['id_number'],
            'password' => bcrypt($data['password']),
        ]);

        return $this->response->array([
            'user' => $user,
        ]);

    }
}

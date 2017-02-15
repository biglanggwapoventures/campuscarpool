<?php

namespace App\Transformers;

use App\User;
use League\Fractal\TransformerAbstract;

class UserListTransformer extends TransformerAbstract
{
    public function transform(User $user)
    {
        return [
           'id' => (int)$user->id,
           'name' => [
               'firstname' => $user->firstname,
               'lastname' =>  $user->lastname,
               'fullname' => $user->fullName(),
           ],
           'id_number' => $user->id_number,
           'joined_at' => $user->created_at->format('m/d/Y h:i A'),
           'display_photo_filename' => asset($user->display_photo),
           'account_type' => ucfirst(strtolower($user->role))
	    ];
    }

}
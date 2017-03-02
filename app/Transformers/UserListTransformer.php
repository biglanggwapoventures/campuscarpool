<?php

namespace App\Transformers;

use App\User;
use League\Fractal\TransformerAbstract;

class UserListTransformer extends TransformerAbstract
{
    public function transform(User $user)
    {
        $rating =  $user->averageRating(true);
        // dd($rating);
        return [
           'id' => (int)$user->id,
           'name' => [
               'firstname' => $user->firstname,
               'lastname' =>  $user->lastname,
               'fullname' => $user->fullName(),
           ],
           'id_number' => $user->id_number,
           'display_photo_filename' => asset($user->display_photo),
           'account_type' => ucfirst(strtolower($user->role)),
           'rating' => $rating ? $rating->avg_rating : 0,
           'num_ratings' => $rating ? $rating->num_ratings : 0,
	    ];
    }

}
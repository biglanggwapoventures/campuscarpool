<?php

namespace App\Transformers;

use App\User;
use League\Fractal\TransformerAbstract;

class CommuterTransformer extends TransformerAbstract
{
    private $dummyPhoto = [
        'user1-128x128.jpg',
        'user2-160x160.jpg',
        'user3-128x128.jpg',
        'user4-128x128.jpg',
        'user5-128x128.jpg',
        'user6-128x128.jpg',
        'user7-128x128.jpg',
        'user8-128x128.jpg',
    ];
    
    public function transform(User $commuter)
    {
        return [
           'id' => (int)$commuter->id,
           'name' => "{$commuter->firstname} {$commuter->lastname}",
           'rating' => rand(1, 5),
           'display_photo_filename' => asset($commuter->display_photo)
	    ];
    }

}
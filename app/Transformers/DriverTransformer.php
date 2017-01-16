<?php

namespace App\Transformers;

use App\User;
use League\Fractal\TransformerAbstract;

class DriverTransformer extends TransformerAbstract
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
    
    public function transform(User $driver)
    {
        return [
           'id' => (int)$driver->id,
           'name' => "{$driver->firstname} {$driver->lastname}",
           'rating' => rand(1, 5),
           'display_photo_filename' => './../images/'.$this->dummyPhoto[rand(0, 7)],
           'vehicle' => "{$driver->profile->vehicle_model} [{$driver->profile->vehicle_plate_number}]"
	    ];
    }

}
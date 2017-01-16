<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = [
        'sender_id',
        'recipient_id',
        'message'
    ];

    public function sender()
    {
        
    }

}

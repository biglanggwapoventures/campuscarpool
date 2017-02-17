<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $fillable = [
        'sender_id',
        'recipient_id',
        'message',
        'driver_route_id'
    ];


    public function recipient()
    {
        return $this->belongsTo('App\User', 'recipient_id')->select('id', 'firstname', 'lastname');
    }

    public function sender()
    {
        return $this->belongsTo('App\User', 'sender_id')->select('id', 'firstname', 'lastname');;
    }
    
}

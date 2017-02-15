<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use DB;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Message;

class User extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'role',
        'firstname', 
        'lastname', 
        'email',
        'id_number',
        'display_photo',
        'password'
    ];

     protected $dates = [
        'approved_at',
        'rejected_at',
        'created_at',
        'deleted_at'
    ];


    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    

    public function profile()
    {
        return $this->hasOne('App\DriverProfile');
    }

    public function rideRequests()
    {
        return $this->hasMany('App\RideRequest', 'commuter_id', 'id');
    }

    public function postedRoutes()
    {
        return $this->hasMany('App\DriverRoute', 'created_by');
    }

    public function messages()
    {
        return $this->hasMany('App\Message', 'sender_id');
    }

    public function conversationWith($userId, $lastMessageId = false)
    {
        $data = Message::where(function($query) USE ($userId){
            $query->where(function($q) USE ($userId){
                $q->where('sender_id', $this->id)->where('recipient_id', $userId);
            })->orWhere(function($q) USE ($userId){
                $q->where('sender_id', $userId)->where('recipient_id', $this->id);
            });
        })
        ->orderBy('created_at', 'DESC');

        if($lastMessageId){
            $data->where('id', '>', $lastMessageId);
        }else{
            $data->limit(20);
        }

        return $data->get();
    }

    public function sendMessage($message, $recipientId)
    {
        return $this->messages()->create([
            'message' => $message,
            'recipient_id' => $recipientId
        ]);
    }

    public function isCommuter()
    {
        return $this->role === 'COMMUTER';
    }

    public function isDriver()
    {
        return $this->role === 'DRIVER';
    }

    public function fullname()
    {
        return "{$this->firstname} {$this->lastname}";
    }

    public function averageRating()
    {
       if($this->isCommuter()){
            $result =  $this->rideRequests()
                ->select(DB::raw('COUNT(id) AS num_ratings, AVG(commuter_rating) AS avg_rating'))
                ->whereNull('cancelled_at')
                ->where([
                    ['accepted', '=', 1],
                    ['commuter_rating', '>', 0]
                ])
                ->groupBy('commuter_id')
                ->having('num_ratings', '>=', 3)
                ->first();
            return $result ? $result->avg_rating : 0;
       }
       
    }
    
}

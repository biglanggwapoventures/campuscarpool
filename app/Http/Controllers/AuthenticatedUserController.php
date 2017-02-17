<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\SendMessageRequest;
use Validator;
use Illuminate\Validation\Rule;
use App\User;

class AuthenticatedUserController extends Controller
{
    public function getDetails()
    {
        $user = $this->auth->user();
        $user->rating = $user->averageRating();
        return $this->response->array([
            'data' =>  $user
        ]);
    }

    public function getConversation($partnerId)
    {
        return $this->response->array([
            'data' =>  $this->auth->user()->conversationWith($partnerId)
        ]);
    }

    public function sendMessage(SendMessageRequest $request)
    {
        $message = $this->auth->user()->sendMessage($request->input('message'), $request->input('recipient_id'));
        if($message->id){
            return $this->response->array([
                'id' => $message->id
            ]);
        }

        return $this->response->errorInternal('An error has occured while trying to send your message');
    }

    public function pollConversation($partnerId, $lastMessageId)
    {
        $data = $this->auth->user()->conversationWith($partnerId, $lastMessageId);
        return $this->response->array([
            'data' => $data
        ]);
    }

    public function updateBasicInformation(Request $request)
    {
        $userId = $this->auth->user()->id;
        $rules = [
            'firstname' => 'required|regex:/^[\pL\s\-]+$/u',
            'lastname' => 'required|regex:/^[\pL\s\-]+$/u',
            'email' => [
                'required', 
                'email',
                 Rule::unique('users')->ignore($userId),
            ],
            'display_photo' => 'image|max:2048'
        ];

        $v = Validator::make($request->all(), $rules);

        if($v->fails()){
            throw new \Dingo\Api\Exception\StoreResourceFailedException('Validation errors..', $v->errors());
        }

        $updates = $request->only([
            'firstname',
            'lastname',
            'email'
        ]);

        if($request->hasFile('display_photo')){
            $displayPhotoFilename = $request->file('display_photo')->store("display_photos/{$userId}", 'public');
            $updates['display_photo'] = "storage/{$displayPhotoFilename}";
        }

        User::find($userId)
            ->fill($updates)
            ->save();

        return $this->response->noContent();
    }

    public function changePassword(Request $request)
    {
        $userId = $this->auth->user()->id;
        $rules = [
            'old' => "required|correct_password:{$userId}",
            'new' => 'required|confirmed',
        ];

        $v = Validator::make($request->all(), $rules);

        if($v->fails()){
            throw new \Dingo\Api\Exception\StoreResourceFailedException('Validation errors..', $v->errors());
        }

         User::find($userId)
            ->fill([
                'password' => bcrypt($request->new)
            ])
            ->save();

        return $this->response->noContent();
    }

    public function getStatus()
    {
        $user = $this->auth->user();
        $lacking = (!$user->school_id_filename) || ($user->isDriver() && !$user->profile()->exists());
       
        return $this->response->array([
            'data' => [
                'lacking' => $lacking ,
                'rejected' => $user->rejected_at
            ]
        ]);
    }
}

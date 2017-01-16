<?php

namespace App\Http\Controllers;

// use Illuminate\Http\Request;
use App\Http\Requests\SendMessageRequest;

class AuthenticatedUserController extends Controller
{
    public function getDetails()
    {
        return $this->response->array([
            'data' =>  $this->auth->user()
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
       return $this->response->array(compact('data'));
        if(count($data)){
             
        }else{
            sleep(1);
            $this->pollConversation($partnerId, $lastMessageId);
        }
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Response;
use Storage;
use App\Models\Conversation;
use App\Http\Resources\Conversation as ConversationResource;

class ConversationController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $customerFilePath = "public/conversations/{$id}/customer.txt";    
        $userFilePath = "public/conversations/{$id}/user.txt";   

        if(!Storage::exists($customerFilePath) ||  !Storage::exists($userFilePath)) {
            return response('No content', 204); // 204 = HTTP_NO_CONTENT 
        }

        $conversation = new Conversation($customerFilePath, $userFilePath);

        return new ConversationResource($conversation);
    }
}

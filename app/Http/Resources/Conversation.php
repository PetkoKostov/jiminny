<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class Conversation extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            "longest_user_monologue" => $this->longestUserMonologue,
            "longest_customer_monologue" => $this->longestCustomerMonologue,
            "user_talk_percentage" => number_format($this->userTalkPercentage, 2, '.', ''),
            "user" => $this->userPoints,
            "customer" => $this->customerPoints,
        ];
    }
}

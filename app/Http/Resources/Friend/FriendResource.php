<?php

namespace App\Http\Resources\Friend;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FriendResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'from_id' => $this->from_name,
            'to_id' => $this->to_id,
            'friend_name' => User::where('id', $this->from_name)
                ->orWhere('id', $this->to_id)->get()
        ];
    }
}

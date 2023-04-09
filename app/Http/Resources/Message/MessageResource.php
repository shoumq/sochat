<?php

namespace App\Http\Resources\Message;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class MessageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->from_name,
            'id' => $this->id,
            'user_id' => $this->from_id,
            'body' => $this->body,
            'time' => $this->created_at->diffForHumans(),
        ];
    }
}

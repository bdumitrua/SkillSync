<?php

namespace App\Http\Resources\Message;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

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
            'text' => $this->text,
            'senderId' => $this->senderId,
            // TODO SENDER DATA
            'senderData' => [],
            'created_at' => $this->created_at,
        ];
    }
}

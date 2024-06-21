<?php

namespace App\Http\Resources\Message;

use Illuminate\Support\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;

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
            'uuid' => $this->uuid,
            'chatId' => $this->chatId,
            'text' => $this->text,
            'status' => $this->status,
            'senderId' => $this->senderId,
            'senderData' => $this->senderData,
            'created_at' => Carbon::createFromTimestampMs($this->created_at),
        ];
    }
}

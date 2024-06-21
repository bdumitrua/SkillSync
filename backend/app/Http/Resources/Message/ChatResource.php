<?php

namespace App\Http\Resources\Message;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;
use App\Http\Resources\User\UserDataResource;
use App\Enums\ChatType;

class ChatResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        if ($this->type === ChatType::Dialog->value) {
            $this->data->membersData = (UserDataResource::collection($this->data->membersData))->resolve();
        }

        return [
            'id' => $this->id,
            'type' => $this->type,
            'isEmpty' => $this->is_empty,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'data' => $this->data,
        ];
    }
}

<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;
use App\Http\Resources\User\UserDataResource;
use App\Http\Resources\Team\TeamDataResource;

class SubscriptionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $subscriberData = !empty($this->subscriberData)
            ? (new UserDataResource($this->subscriberData))->resolve()
            : [];

        if ($entityData = !empty($this->entityData)) {
            if ($this->entity_type === config('entities.user')) {
                $entityData = (new UserDataResource($this->entityData));
            } elseif ($this->entity_type === config('entities.team')) {
                $entityData = (new TeamDataResource($this->entityData));
            }
        }

        return [
            "id" => $this->id,
            "subscriberId" => $this->subscriber_id,
            "entityType" => $this->entity_type,
            "entityId" => $this->entity_id,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
            "subscriberData" => $subscriberData,
            "entityData" => $entityData,
        ];
    }
}

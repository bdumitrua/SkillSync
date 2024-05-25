<?php

namespace App\Http\Resources\Auth;

use App\Http\Resources\ActionsResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RegistrationCodeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $registrationId = $this->resource;
        $actions = (array) ActionsResource::collection([
            [
                "ConfirmCode",
                "confirmRegistration",
                ['authRegistration' => $registrationId],
            ]
        ]);

        return [
            'registrationId' => $registrationId,
            'actions' => $actions,
        ];
    }
}

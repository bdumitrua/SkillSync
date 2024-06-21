<?php

namespace App\Http\Resources\Team;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;
use App\Http\Resources\TagResource;
use App\Http\Resources\Message\ChatResource;
use App\Http\Resources\ActionsResource;

class TeamResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $tags = !empty($this->tags) ? TagResource::collection($this->tags) : [];
        $links = !empty($this->links) ? TeamLinkResource::collection($this->links) : [];

        $actions = $this->prepareActions();

        return [
            'id' => $this->id,
            'name' => $this->name,
            'avatar' => $this->avatar,
            'description' => $this->description,
            'email' => $this->email,
            'site' => $this->site,
            'chatId' => $this->chat_id,
            'adminId' => $this->admin_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'tags' => $tags,
            'links' => $links,
            'actions' => $actions,
        ];
    }

    private function prepareActions(): array
    {
        $actions = [
            [
                "GetTeamMembers",
                "teams.members.team",
                ["team" => $this->id]
            ],
            [
                "GetTeamSubscribers",
                "subscriptions.to.team",
                ["team" => $this->id]
            ],
            [
                "GetTeamVacancies",
                "teams.vacancies.team",
                ["team" => $this->id]
            ],
            [
                "GetTeamPosts",
                "posts.team",
                ["team" => $this->id]
            ],
        ];

        return (array) ActionsResource::collection($actions);
    }
}

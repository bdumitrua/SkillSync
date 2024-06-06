<?php

namespace App\Http\Resources\Team;

use App\Http\Resources\ActionsResource;
use App\Http\Resources\Message\ChatResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TeamResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $chatData = $this->chatData ? (new ChatResource($this->chatData))->resolve() : [];

        $actions = $this->prepareActions();

        return [
            'id' => $this->id,
            'name' => $this->name,
            'avatar' => $this->avatar,
            'description' => $this->description,
            'email' => $this->email,
            'site' => $this->site,
            'chatId' => $this->chat_id,
            'chatData' => $chatData,
            'adminId' => $this->admin_id,
            'posts' => $this->posts,
            'tags' => $this->tags,
            'links' => $this->links,
            'actions' => $actions
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
                "teams.subscribers",
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

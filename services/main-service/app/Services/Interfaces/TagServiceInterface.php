<?php

namespace App\Services\Interfaces;

use App\Models\Tag;
use App\Http\Requests\CreateTagRequest;
use Illuminate\Http\Resources\Json\JsonResource;

interface TagServiceInterface
{
    /**
     * @param int $userId
     * 
     * @return JsonResource
     */
    public function user(int $userId): JsonResource;

    /**
     * @param int $teamId
     * 
     * @return JsonResource
     */
    public function team(int $teamId): JsonResource;

    /**
     * @param CreateTagRequest $request
     * 
     * @return void
     */
    public function create(CreateTagRequest $request): void;

    /**
     * @param Tag $tag
     * 
     * @return void
     */
    public function delete(Tag $tag): void;
}

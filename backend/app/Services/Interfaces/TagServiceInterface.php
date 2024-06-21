<?php

namespace App\Services\Interfaces;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Tag;
use App\Http\Requests\CreateTagRequest;
use App\DTO\CreateTagDTO;

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
     * @param CreateTagDTO $createTagDTO
     * 
     * @return void
     */
    public function create(CreateTagDTO $createTagDTO): void;

    /**
     * @param Tag $tag
     * 
     * @return void
     */
    public function delete(Tag $tag): void;
}

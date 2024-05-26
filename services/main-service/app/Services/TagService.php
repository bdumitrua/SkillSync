<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use App\Services\Interfaces\TagServiceInterface;
use App\Models\Tag;
use App\Http\Requests\CreateTagRequest;
use App\Http\Resources\TagResource;
use App\Repositories\Interfaces\TagRepositoryInterface;
use Illuminate\Http\Resources\Json\JsonResource;

class TagService implements TagServiceInterface
{
    private $tagRepository;
    private ?int $authorizedUserId;

    public function __construct(
        TagRepositoryInterface $tagRepository,
    ) {
        $this->tagRepository = $tagRepository;
        $this->authorizedUserId = Auth::id();
    }

    public function user(int $userId): JsonResource
    {
        return TagResource::collection(
            $this->tagRepository->getByUserId($userId)
        );
    }

    public function team(int $teamId): JsonResource
    {
        return TagResource::collection(
            $this->tagRepository->getByTeamId($teamId)
        );
    }

    public function create(CreateTagRequest $request): void
    {
        // TODO GATE: Check if: 
        // is authorized user itself
        // is moderator of team
        // is moderator of team who posted
    }

    public function delete(Tag $tag): void
    {
        // TODO GATE: Check if: 
        // is authorized user itself
        // is moderator of team
        // is moderator of team who posted
    }
}

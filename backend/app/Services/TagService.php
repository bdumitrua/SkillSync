<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Traits\Dtoable;
use App\Services\Interfaces\TagServiceInterface;
use App\Repositories\Interfaces\TagRepositoryInterface;
use App\Models\Tag;
use App\Http\Resources\TagResource;
use App\Http\Requests\CreateTagRequest;
use App\DTO\CreateTagDTO;

class TagService implements TagServiceInterface
{
    protected $tagRepository;
    protected ?int $authorizedUserId;

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

    public function create(CreateTagDTO $createTagDTO): void
    {
        Gate::authorize('create', [Tag::class, $createTagDTO->entityType, $createTagDTO->entityId]);

        $this->tagRepository->create($createTagDTO);
    }

    public function delete(Tag $tag): void
    {
        Gate::authorize('delete', [Tag::class, $tag]);

        $this->tagRepository->delete($tag);
    }
}

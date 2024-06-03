<?php

namespace App\Services;

use App\DTO\User\CreateTagDTO;
use Illuminate\Support\Facades\Auth;
use App\Services\Interfaces\TagServiceInterface;
use App\Models\Tag;
use App\Http\Requests\CreateTagRequest;
use App\Http\Resources\TagResource;
use App\Repositories\Interfaces\TagRepositoryInterface;
use App\Traits\CreateDTO;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class TagService implements TagServiceInterface
{
    use CreateDTO;

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
        $this->patchCreateTagRequest($request);

        Gate::authorize(CREATE_TAG_GATE, [Tag::class, $request->entityType, $request->entityId]);

        $createTagDTO = $this->createDTO($request, CreateTagDTO::class);
        $this->tagRepository->create($createTagDTO);
    }

    public function delete(Tag $tag): void
    {
        Gate::authorize(DELETE_TAG_GATE, [Tag::class, $tag]);

        $this->tagRepository->delete($tag);
    }

    protected function patchCreateTagRequest(CreateTagRequest &$request): void
    {
        Log::debug('Patching CreateTagRequest', ['request' => $request->toArray()]);
        $entitiesPath = config('entities');

        $request->merge([
            'entityType' => $entitiesPath[$request->entityType]
        ]);
        Log::debug('Patched CreateTagRequest', ['request' => $request->toArray()]);
    }
}

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

        $createTagDTO = $this->createDTO($request, CreateTagDTO::class);
        $this->tagRepository->create($createTagDTO);
    }

    public function delete(Tag $tag): void
    {
        // TODO GATE: Check if: 
        // is authorized user itself
        // is moderator of team
        // is moderator of team who posted
        $this->tagRepository->delete($tag);
    }

    protected function patchCreateTagRequest(CreateTagRequest &$request): void
    {
        $entitiesPath = config('entities');

        $request->merge([
            'entity_type' => $entitiesPath[$request->entity_type]
        ]);

        if ($request->entity_type == 'user') {
            $request->merge([
                'entity_id' => $this->authorizedUserId
            ]);
        } elseif ($request->entity_type == 'team') {
            // TODO GATE: Check if user is moderator
        } elseif ($request->entity_type == 'post') {
            // TODO GATE: Check if is author or moderator of team who posted
        }
    }
}

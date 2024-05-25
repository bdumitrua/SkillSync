<?php

namespace App\Services\Team;

use App\Services\Team\Interfaces\TeamServiceInterface;
use App\Repositories\Team\Interfaces\TeamRepositoryInterface;
use App\Models\Team;
use App\Http\Requests\Team\UpdateTeamRequest;
use App\Http\Requests\Team\CreateTeamRequest;
use App\Http\Resources\Team\TeamDataResource;
use App\Http\Resources\Team\TeamResource;
use App\Services\Interfaces\TagServiceInterface;
use App\Services\Team\Interfaces\TeamLinkServiceInterface;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class TeamService implements TeamServiceInterface
{
    protected $tagService;
    protected $teamRepository;
    protected $teamLinkService;
    protected ?int $authorizedUserId;

    public function __construct(
        TagServiceInterface $tagService,
        TeamRepositoryInterface $teamRepository,
        TeamLinkServiceInterface $teamLinkService,
    ) {
        $this->tagService = $tagService;
        $this->teamRepository = $teamRepository;
        $this->teamLinkService = $teamLinkService;
        $this->authorizedUserId = Auth::id();
    }

    public function index(): JsonResource
    {
        return TeamDataResource::collection(
            $this->teamRepository->getAll()
        );
    }

    public function show(int $teamId): JsonResource
    {
        $team = $this->teamRepository->getById($teamId);
        $team = $this->assembleTeam($team);

        return new TeamResource($team);
    }

    public function user(int $userId): JsonResource
    {
        return TeamDataResource::collection(
            $this->teamRepository->getByUserId($userId)
        );
    }

    public function create(CreateTeamRequest $request): void
    {
        //
    }

    public function update(Team $team, UpdateTeamRequest $request): void
    {
        //
    }

    public function delete(Team $team): void
    {
        //
    }

    protected function assembleTeam(Team $team): Team
    {
        $team->links = $this->teamLinkService->team($team->id);
        $team->tags = $this->tagService->team($team->id);

        return $team;
    }
}

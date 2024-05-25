<?php

namespace App\Services\Team;

use App\DTO\Team\CreateTeamDTO;
use App\DTO\Team\CreateTeamMemberDTO;
use App\DTO\Team\UpdateTeamDTO;
use App\Services\Team\Interfaces\TeamServiceInterface;
use App\Repositories\Team\Interfaces\TeamRepositoryInterface;
use App\Models\Team;
use App\Http\Requests\Team\UpdateTeamRequest;
use App\Http\Requests\Team\CreateTeamRequest;
use App\Http\Resources\Team\TeamDataResource;
use App\Http\Resources\Team\TeamResource;
use App\Repositories\Team\Interfaces\TeamMemberRepositoryInterface;
use App\Services\Interfaces\TagServiceInterface;
use App\Services\Team\Interfaces\TeamLinkServiceInterface;
use App\Traits\CreateDTO;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class TeamService implements TeamServiceInterface
{
    use CreateDTO;

    protected $tagService;
    protected $teamLinkService;
    protected $teamRepository;
    protected $teamMemberRepository;
    protected ?int $authorizedUserId;

    public function __construct(
        TagServiceInterface $tagService,
        TeamLinkServiceInterface $teamLinkService,
        TeamRepositoryInterface $teamRepository,
        TeamMemberRepositoryInterface $teamMemberRepository,
    ) {
        $this->tagService = $tagService;
        $this->teamLinkService = $teamLinkService;
        $this->teamRepository = $teamRepository;
        $this->teamMemberRepository = $teamMemberRepository;
        $this->authorizedUserId = Auth::id();
    }

    public function index(): JsonResource
    {
        return TeamResource::collection(
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
        $userTeamsIds = $this->teamMemberRepository->getByUserId($userId)
            ->pluck('team_id')->unique()->all();

        return TeamDataResource::collection(
            $this->teamRepository->getByIds($userTeamsIds)
        );
    }

    public function create(CreateTeamRequest $request): void
    {
        /** @var CreateTeamDTO */
        $createTeamDTO = $this->createDTO($request, CreateTeamDTO::class);
        $createTeamDTO->adminId = $this->authorizedUserId;
        $newTeam = $this->teamRepository->create($createTeamDTO);

        $createTeamMemberDTO = new CreateTeamMemberDTO($this->authorizedUserId, $newTeam->id, isModerator: true);
        $this->teamMemberRepository->addMember($createTeamMemberDTO);
    }

    public function update(Team $team, UpdateTeamRequest $request): void
    {
        $updateTeamDTO = $this->createDTO($request, UpdateTeamDTO::class);
        $this->teamRepository->update($team, $updateTeamDTO);
    }

    public function delete(Team $team): void
    {
        $this->teamRepository->delete($team);
    }

    protected function assembleTeam(Team $team): Team
    {
        $team->links = $this->teamLinkService->team($team->id);
        $team->tags = $this->tagService->team($team->id);

        return $team;
    }
}

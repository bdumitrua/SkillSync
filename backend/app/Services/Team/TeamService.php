<?php

namespace App\Services\Team;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Services\Team\Interfaces\TeamServiceInterface;
use App\Repositories\User\Interfaces\UserRepositoryInterface;
use App\Repositories\Team\Interfaces\TeamRepositoryInterface;
use App\Repositories\Team\Interfaces\TeamMemberRepositoryInterface;
use App\Repositories\Team\Interfaces\TeamLinkRepositoryInterface;
use App\Repositories\Interfaces\TagRepositoryInterface;
use App\Repositories\Interfaces\SubscriptionRepositoryInterface;
use App\Models\Team;
use App\Http\Resources\Team\TeamResource;
use App\Http\Resources\Team\TeamDataResource;
use App\DTO\Team\UpdateTeamDTO;
use App\DTO\Team\CreateTeamMemberDTO;
use App\DTO\Team\CreateTeamDTO;

class TeamService implements TeamServiceInterface
{
    protected $tagRepository;
    protected $userRepository;
    protected $teamRepository;
    protected $teamLinkRepository;
    protected $teamMemberRepository;
    protected $subscriptionRepository;
    protected ?int $authorizedUserId;

    public function __construct(
        TagRepositoryInterface $tagRepository,
        UserRepositoryInterface $userRepository,
        TeamRepositoryInterface $teamRepository,
        TeamLinkRepositoryInterface $teamLinkRepository,
        TeamMemberRepositoryInterface $teamMemberRepository,
        SubscriptionRepositoryInterface $subscriptionRepository,
    ) {
        $this->tagRepository = $tagRepository;
        $this->userRepository = $userRepository;
        $this->teamRepository = $teamRepository;
        $this->teamLinkRepository = $teamLinkRepository;
        $this->teamMemberRepository = $teamMemberRepository;
        $this->subscriptionRepository = $subscriptionRepository;
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

    public function search(string $query): JsonResource
    {
        $teams = $this->teamRepository->search($query);

        return TeamDataResource::collection($teams);
    }

    public function user(int $userId): JsonResource
    {
        $userTeamsIds = $this->teamMemberRepository->getByUserId($userId)
            ->pluck('team_id')->unique()->all();

        return TeamDataResource::collection(
            $this->teamRepository->getByIds($userTeamsIds)
        );
    }

    public function create(CreateTeamDTO $createTeamDTO): void
    {
        $newTeam = $this->teamRepository->create($createTeamDTO);

        $createTeamMemberDTO = new CreateTeamMemberDTO(
            $this->authorizedUserId,
            $newTeam->id,
            isModerator: true
        );

        $this->teamMemberRepository->addMember($createTeamMemberDTO);
    }

    public function update(Team $team, UpdateTeamDTO $updateTeamDTO): void
    {
        Gate::authorize('update', [Team::class, $team->id]);

        $this->teamRepository->update($team, $updateTeamDTO);
    }

    public function delete(Team $team): void
    {
        Gate::authorize('delete', [Team::class, $team->id]);

        $this->teamRepository->delete($team);
    }

    protected function assembleTeam(Team $team): Team
    {
        $isTeamMember = $this->teamMemberRepository->userIsMember($team->id, $this->authorizedUserId);

        $team->links = $this->teamLinkRepository->getByTeamId($team->id, $isTeamMember);
        $team->tags = $this->tagRepository->getByEntityId($team->id, config('entities.team'));

        return $team;
    }
}

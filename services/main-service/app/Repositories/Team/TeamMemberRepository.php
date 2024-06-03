<?php

namespace App\Repositories\Team;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Builder;
use App\Traits\UpdateFromDTO;
use App\Repositories\Team\Interfaces\TeamMemberRepositoryInterface;
use App\Models\TeamMember;
use App\DTO\Team\UpdateTeamMemberDTO;
use App\DTO\Team\CreateTeamMemberDTO;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class TeamMemberRepository implements TeamMemberRepositoryInterface
{
    use UpdateFromDTO;

    protected function queryByBothIds(int $teamId, int $userId): Builder
    {
        return TeamMember::query()
            ->where('team_id', '=', $teamId)
            ->where('user_id', '=', $userId);
    }

    public function getByTeamId(int $teamId): Collection
    {
        Log::debug('Getting team members', [
            'teamId' => $teamId
        ]);

        return TeamMember::where('team_id', '=', $teamId)->get();
    }

    public function getByUserId(int $userId): Collection
    {
        Log::debug('Getting user teams', [
            'userId' => $userId
        ]);

        return TeamMember::where('user_id', '=', $userId)->get();
    }

    public function getMemberByBothIds(int $teamId, int $userId): ?TeamMember
    {
        Log::debug('Getting user membership', [
            'teamId' => $teamId,
            'userId' => $userId,
        ]);

        return $this->queryByBothIds($teamId, $userId)->first();
    }

    public function userIsMember(int $teamId, int $userId): bool
    {
        Log::debug('Checking user membership', [
            'teamId' => $teamId,
            'userId' => $userId,
        ]);

        return $this->queryByBothIds($teamId, $userId)->exists();
    }

    public function userIsModerator(int $teamId, int $userId): bool
    {
        Log::debug('Checking if user is moderator in team', [
            'teamId' => $teamId,
            'userId' => $userId,
        ]);

        $membership = $this->getMemberByBothIds($teamId, $userId);

        if (empty($membership)) {
            return false;
        }

        return $membership->is_moderator;
    }

    public function addMember(CreateTeamMemberDTO $dto): void
    {
        Log::debug('Adding user to team members from dto', [
            'dto' => $dto
        ]);

        TeamMember::create(
            $dto->toArray()
        );
    }

    public function updateMember(TeamMember $teamMember, UpdateTeamMemberDTO $dto): void
    {
        Log::debug('updating user team membership data from dto', [
            'dto' => $dto
        ]);

        $this->updateFromDto($teamMember, $dto);
    }

    public function removeMember(TeamMember $teamMember): void
    {
        Log::debug('Removing user from team members', [
            'teamMember' => $teamMember->toArray(),
            'authorizedUserId' => Auth::id()
        ]);
        $teamMember->delete();
    }
}

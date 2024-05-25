<?php

namespace App\Repositories\Team;

use App\DTO\Team\CreateTeamMemberDTO;
use App\Repositories\Team\Interfaces\TeamMemberRepositoryInterface;
use App\Models\TeamMember;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class TeamMemberRepository implements TeamMemberRepositoryInterface
{
    protected function queryByBothIds(int $teamId, int $userId): Builder
    {
        return TeamMember::query()
            ->where('team_id', '=', $teamId)
            ->where('user_id', '=', $userId);
    }

    public function getByTeamId(int $teamId): Collection
    {
        return TeamMember::where('team_id', '=', $teamId)->get();
    }

    public function getByUserId(int $userId): Collection
    {
        return TeamMember::where('user_id', '=', $userId)->get();
    }

    public function getMemberByBothIds(int $teamId, int $userId): ?TeamMember
    {
        return $this->queryByBothIds($teamId, $userId)->first();
    }

    public function userIsMember(int $teamId, int $userId): bool
    {
        return $this->queryByBothIds($teamId, $userId)->exists();
    }

    public function addMember(CreateTeamMemberDTO $dto): void
    {
        TeamMember::create(
            $dto->toArray()
        );
    }

    public function removeMember(TeamMember $teamMember): void
    {
        $teamMember->delete();
    }
}

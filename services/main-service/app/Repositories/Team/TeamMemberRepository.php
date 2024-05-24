<?php

namespace App\Repositories\Team;

use App\Repositories\Team\Interfaces\TeamMemberRepositoryInterface;
use App\Models\TeamMember;
use Illuminate\Database\Eloquent\Collection;

class TeamMemberRepository implements TeamMemberRepositoryInterface
{
    public function getByTeamId(int $teamId): Collection
    {
        return new Collection();
    }

    public function getByUserId(int $userId): Collection
    {
        return new Collection();
    }

    public function getMemberByBothIds(int $teamId, int $userId): ?TeamMember
    {
        return null;
    }

    public function addMember(TeamMember $teamMember): ?TeamMember
    {
        return null;
    }

    public function removeMember(int $teamId, array $data): ?bool
    {
        return null;
    }
}

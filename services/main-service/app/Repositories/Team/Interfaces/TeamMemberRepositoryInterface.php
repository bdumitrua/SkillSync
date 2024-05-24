<?php

namespace App\Repositories\Team\Interfaces;

use App\Models\TeamMember;
use Illuminate\Database\Eloquent\Collection;

interface TeamMemberRepositoryInterface
{
    /**
     * @param int $teamId
     * 
     * @return Collection
     */
    public function getByTeamId(int $teamId): Collection;
    /**
     * @param int $userId
     * 
     * @return Collection
     */
    public function getByUserId(int $userId): Collection;
    /**
     * @param int $teamId
     * @param int $userId
     * 
     * @return TeamMember|null
     */
    public function getMemberByBothIds(int $teamId, int $userId): ?TeamMember;
    /**
     * @param TeamMember $teamMember
     * 
     * @return TeamMember|null
     */
    public function addMember(TeamMember $teamMember): ?TeamMember;
    /**
     * @param int $teamId
     * @param array $data
     * 
     * @return bool|null
     */
    public function removeMember(int $teamId, array $data): ?bool;
}

<?php

namespace App\Repositories\Team\Interfaces;

use Illuminate\Database\Eloquent\Collection;
use App\Models\TeamMember;
use App\DTO\Team\UpdateTeamMemberDTO;
use App\DTO\Team\CreateTeamMemberDTO;

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
     * @param int $teamId
     * @param int $userId
     * 
     * @return bool
     */
    public function userIsMember(int $teamId, int $userId): bool;

    /**
     * @param int $teamId
     * @param int $userId
     * 
     * @return bool
     */
    public function userIsModerator(int $teamId, int $userId): bool;

    /**
     * @param CreateTeamMemberDTO $dto
     * 
     * @return void
     */
    public function addMember(CreateTeamMemberDTO $dto): void;

    /**
     * @param TeamMember $teamMember
     * @param UpdateTeamMemberDTO $dto
     * 
     * @return void
     */
    public function updateMember(TeamMember $teamMember, UpdateTeamMemberDTO $dto): void;

    /**
     * @param TeamMember $teamMember
     * 
     * @return void
     */
    public function removeMember(TeamMember $teamMember): void;
}

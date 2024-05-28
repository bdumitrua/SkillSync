<?php

namespace App\Repositories\Team;

use Illuminate\Database\Eloquent\Collection;
use App\Repositories\Team\Interfaces\TeamLinkRepositoryInterface;
use App\Models\TeamLink;
use App\DTO\Team\UpdateTeamLinkDTO;
use App\DTO\Team\CreateTeamLinkDTO;
use App\Traits\GetCachedData;
use App\Traits\UpdateFromDTO;

class TeamLinkRepository implements TeamLinkRepositoryInterface
{
    use UpdateFromDTO, GetCachedData;

    public function getByTeamId(int $teamId, bool $isMember): Collection
    {
        $cacheKey = $this->getTeamLinksCacheKey($teamId, $isMember);
        return $this->getCachedData($cacheKey, CACHE_TIME_TEAM_LINKS_DATA, function () use ($teamId, $isMember) {
            $teamLinksQuery = TeamLink::query()->where('team_id', '=', $teamId);

            if (!$isMember) {
                $teamLinksQuery = $teamLinksQuery->where('is_private', '=', false);
            }

            return $teamLinksQuery->get();
        });
    }

    public function create(CreateTeamLinkDTO $dto): TeamLink
    {
        $newTeamLink = TeamLink::create(
            $dto->toArray()
        );

        return $newTeamLink;
    }

    public function update(TeamLink $teamLink, UpdateTeamLinkDTO $dto): void
    {
        $this->updateFromDto($teamLink, $dto);
        $this->clearTeamLinksCache($teamLink->team_id);
    }

    public function delete(TeamLink $teamLink): void
    {
        $teamId = $teamLink->team_id;

        $teamLink->delete();
        $this->clearTeamLinksCache($teamId);
    }

    protected function getTeamLinksCacheKey(int $teamId, bool $isMember): string
    {
        return CACHE_KEY_TEAM_LINKS_DATA . $teamId . ":" . $isMember;
    }

    protected function clearTeamLinksCache(int $teamId): void
    {
        $this->clearCache($this->getTeamLinksCacheKey($teamId, false));
        $this->clearCache($this->getTeamLinksCacheKey($teamId, true));
    }
}

<?php

namespace App\Repositories\Team;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Collection;
use App\Traits\UpdateFromDTO;
use App\Traits\Cacheable;
use App\Repositories\Team\Interfaces\TeamLinkRepositoryInterface;
use App\Models\TeamLink;
use App\DTO\Team\UpdateTeamLinkDTO;
use App\DTO\Team\CreateTeamLinkDTO;

class TeamLinkRepository implements TeamLinkRepositoryInterface
{
    use UpdateFromDTO, Cacheable;

    public function getByTeamId(int $teamId, bool $isMember): Collection
    {
        Log::debug("Getting teamLinks", [
            'teamId' => $teamId,
            'isMember' => $isMember,
            'authorizedUserId' => Auth::id()
        ]);

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
        Log::debug('Creating teamLink from dto', [
            'dto' => $dto->toArray()
        ]);

        $newTeamLink = TeamLink::create(
            $dto->toArray()
        );

        $this->clearTeamLinksCache($newTeamLink->team_id);

        Log::debug('Succesfully created teamLink from dto', [
            'dto' => $dto->toArray(),
            'newTeamLink' => $newTeamLink->toArray()
        ]);

        return $newTeamLink;
    }

    public function update(TeamLink $teamLink, UpdateTeamLinkDTO $dto): void
    {
        Log::debug('Updating teamLink from dto', [
            'teamLink id' => $teamLink->id,
            'dto' => $dto->toArray()
        ]);

        $this->updateFromDto($teamLink, $dto);
        $this->clearTeamLinksCache($teamLink->team_id);

        Log::debug('Succesfully updated teamLink from dto', [
            'teamLink id' => $teamLink->id,
        ]);
    }

    public function delete(TeamLink $teamLink): void
    {
        $teamId = $teamLink->team_id;
        $teamLinkData = $teamLink->toArray();

        Log::debug('Deleting teamLink', [
            'teamLinkData' => $teamLinkData,
            'authorizedUserId' => Auth::id()
        ]);

        $teamLink->delete();
        $this->clearTeamLinksCache($teamId);

        Log::debug('Succesfully deleted teamLink', [
            'teamLinkData' => $teamLinkData,
        ]);
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

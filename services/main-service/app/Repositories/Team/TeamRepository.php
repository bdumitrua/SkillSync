<?php

namespace App\Repositories\Team;

use App\DTO\Team\CreateTeamDTO;
use App\DTO\Team\UpdateTeamDTO;
use App\Repositories\Team\Interfaces\TeamRepositoryInterface;
use App\Models\Team;
use App\Traits\GetCachedData;
use App\Traits\UpdateFromDTO;
use Illuminate\Database\Eloquent\Collection;

class TeamRepository implements TeamRepositoryInterface
{
    use UpdateFromDTO, GetCachedData;

    public function getAll(): Collection
    {
        return Team::get();
    }

    public function getById(int $teamId): ?Team
    {
        $cacheKey = $this->getTeamCacheKey($teamId);
        return $this->getCachedData($cacheKey, CACHE_TIME_TEAM_DATA, function () use ($teamId) {
            return Team::find($teamId);
        });
    }

    public function getByIds(array $teamIds): Collection
    {
        return $this->getCachedCollection($teamIds, function ($id) {
            return $this->getById($id);
        });
    }

    // TODO CACHE AFTER MESSAGING
    public function getByChatId(int $chatId): ?Team
    {
        return Team::where('chat_id', '=', $chatId)->first();
    }

    public function create(CreateTeamDTO $dto): Team
    {
        $newTeam = Team::create(
            $dto->toArray()
        );

        return $newTeam;
    }

    public function update(Team $team, UpdateTeamDTO $dto): void
    {
        $this->updateFromDto($team, $dto);
        $this->clearTeamCache($team->id);
    }

    public function delete(Team $team): void
    {
        $teamId = $team->id;

        $team->delete();
        $this->clearTeamCache($teamId);
    }

    protected function getTeamCacheKey(int $teamId): string
    {
        return CACHE_KEY_TEAM_DATA . $teamId;
    }

    protected function clearTeamCache(int $teamId): void
    {
        $this->clearCache($this->getTeamCacheKey($teamId));
    }
}

<?php

namespace App\Repositories\Team;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Collection;
use App\Traits\Updateable;
use App\Traits\Cacheable;
use App\Repositories\Team\Interfaces\TeamRepositoryInterface;
use App\Models\Team;
use App\DTO\Team\UpdateTeamDTO;
use App\DTO\Team\CreateTeamDTO;

class TeamRepository implements TeamRepositoryInterface
{
    use Updateable, Cacheable;

    public function getAll(): Collection
    {
        return Team::get();
    }

    public function getById(int $teamId): ?Team
    {
        Log::debug('Getting team by id', [
            'teamId' => $teamId
        ]);

        $cacheKey = $this->getTeamCacheKey($teamId);
        return $this->getCachedData($cacheKey, CACHE_TIME_TEAM_DATA, function () use ($teamId) {
            return Team::find($teamId);
        });
    }

    public function getByIds(array $teamIds): Collection
    {
        Log::debug('Getting teams by ids', [
            'teamIds' => $teamIds
        ]);

        return $this->getCachedCollection($teamIds, function ($id) {
            return $this->getById($id);
        });
    }

    public function search(string $query): Collection
    {
        return Team::search($query);
    }

    public function getByChatId(int $chatId): ?Team
    {
        Log::debug('Getting team by chatId', [
            'chatId' => $chatId
        ]);

        return Team::where('chat_id', '=', $chatId)->first();
    }

    public function create(CreateTeamDTO $dto): Team
    {
        Log::debug('Creating team from dto', [
            'dto' => $dto->toArray()
        ]);

        $newTeam = Team::create(
            $dto->toArray()
        );

        Log::debug('Succesfully created team from dto', [
            'dto' => $dto->toArray(),
            'newTeam' => $newTeam->toArray()
        ]);

        return $newTeam;
    }

    public function update(Team $team, UpdateTeamDTO $dto): void
    {
        Log::debug('Updating team from dto', [
            'team id' => $team->id,
            'dto' => $dto->toArray()
        ]);

        $this->updateFromDto($team, $dto);

        Log::debug('Succesfully updated team from dto', [
            'team id' => $team->id,
        ]);
    }

    public function delete(Team $team): void
    {
        $teamData = $team->toArray();

        Log::debug('Deleting team', [
            'teamData' => $teamData,
            'authorizedUserId' => Auth::id()
        ]);

        $team->delete();

        Log::debug('Succesfully deleted team', [
            'teamData' => $teamData,
        ]);
    }

    protected function getTeamCacheKey(int $teamId): string
    {
        return CACHE_KEY_TEAM_DATA . $teamId;
    }
}

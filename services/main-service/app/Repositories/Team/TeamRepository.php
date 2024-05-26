<?php

namespace App\Repositories\Team;

use App\DTO\Team\CreateTeamDTO;
use App\DTO\Team\UpdateTeamDTO;
use App\Repositories\Team\Interfaces\TeamRepositoryInterface;
use App\Models\Team;
use App\Traits\UpdateFromDTO;
use Illuminate\Database\Eloquent\Collection;

class TeamRepository implements TeamRepositoryInterface
{
    use UpdateFromDTO;

    public function getAll(): Collection
    {
        return Team::get();
    }

    public function getById(int $teamId): ?Team
    {
        return Team::find($teamId);
    }

    public function getByIds(array $teamIds): Collection
    {
        return Team::whereIn('id', $teamIds)->get();
    }

    public function getByName(string $teamName): ?Team
    {
        return Team::where('name', '=', $teamName)->first();
    }

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
    }

    public function delete(Team $team): void
    {
        $team->delete();
    }
}

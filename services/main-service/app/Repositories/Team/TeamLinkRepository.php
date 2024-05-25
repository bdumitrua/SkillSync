<?php

namespace App\Repositories\Team;

use Illuminate\Database\Eloquent\Collection;
use App\Repositories\Team\Interfaces\TeamLinkRepositoryInterface;
use App\Models\TeamLink;
use App\Http\Requests\Team\UpdateTeamLinkRequest;
use App\DTO\Team\UpdateTeamLinkDTO;
use App\DTO\Team\CreateTeamLinkDTO;
use App\Traits\UpdateFromDTO;

class TeamLinkRepository implements TeamLinkRepositoryInterface
{
    use UpdateFromDTO;

    public function getByTeamId(int $teamId, bool $isMember): Collection
    {
        $teamLinksQuery = TeamLink::query()->where('team_id', '=', $teamId);

        if (!$isMember) {
            $teamLinksQuery = $teamLinksQuery->where('is_private', '=', false);
        }

        return $teamLinksQuery->get();
    }

    public function getById(int $teamLinkId): ?TeamLink
    {
        return TeamLink::find($teamLinkId);
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
    }

    public function delete(TeamLink $teamLink): void
    {
        $teamLink->delete();
    }
}

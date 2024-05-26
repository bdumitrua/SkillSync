<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Collection;
use App\Repositories\Interfaces\TagRepositoryInterface;
use App\Models\Tag;
use App\DTO\User\CreateTagDTO;
use Illuminate\Database\Eloquent\Builder;

class TagRepository implements TagRepositoryInterface
{
    protected function queryByUser(): Builder
    {
        return Tag::query()->where('entity_type', '=', config('entities.user'));
    }

    protected function queryByTeam(): Builder
    {
        return Tag::query()->where('entity_type', '=', config('entities.team'));
    }

    protected function queryByPost(): Builder
    {
        return Tag::query()->where('entity_type', '=', config('entities.post'));
    }

    public function getByUserId(int $userId): Collection
    {
        return $this->queryByUser()->where('entity_id', '=', $userId)->get();
    }

    public function getByUserIds(array $userIds): Collection
    {
        return $this->queryByUser()
            ->whereIn('entity_id', $userIds)
            ->groupBy('entity_id')
            ->get();
    }

    public function getByTeamId(int $teamId): Collection
    {
        return $this->queryByTeam()->where('entity_id', '=', $teamId)->get();
    }

    public function getByTeamIds(array $teamIds): Collection
    {
        return $this->queryByTeam()
            ->whereIn('entity_id', $teamIds)
            ->groupBy('entity_id')
            ->get();
    }

    public function getByPostId(int $postId): Collection
    {
        return $this->queryByPost()->where('entity_id', '=', $postId)->get();
    }

    public function getByPostIds(array $postIds): Collection
    {
        return $this->queryByPost()
            ->whereIn('entity_id', $postIds)
            ->groupBy('entity_id')
            ->get();
    }

    public function create(CreateTagDTO $dto): Tag
    {
        $newTag = Tag::create(
            $dto->toArray()
        );

        return $newTag;
    }

    public function delete(Tag $tag): void
    {
        $tag->delete();
    }
}

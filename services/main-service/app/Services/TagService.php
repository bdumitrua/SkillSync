<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use App\Services\Interfaces\TagServiceInterface;
use App\Models\Tag;
use App\Http\Requests\CreateTagRequest;
use App\Repositories\Interfaces\TagRepositoryInterface;

class TagService implements TagServiceInterface
{
    private $tagRepository;
    private ?int $authorizedUserId;

    public function __construct(
        TagRepositoryInterface $tagRepository,
    ) {
        $this->tagRepository = $tagRepository;
        $this->authorizedUserId = Auth::id();
    }

    public function create(CreateTagRequest $request): void
    {
        // 
    }

    public function delete(Tag $tag): void
    {
        // 
    }
}

<?php

namespace App\Services\Interfaces;

use App\Models\Tag;
use App\Http\Requests\CreateTagRequest;

interface TagServiceInterface
{
    /**
     * @param CreateTagRequest $request
     * 
     * @return void
     */
    public function create(CreateTagRequest $request): void;

    /**
     * @param Tag $tag
     * 
     * @return void
     */
    public function delete(Tag $tag): void;
}

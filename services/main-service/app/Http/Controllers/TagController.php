<?php

namespace App\Http\Controllers;

use App\Services\Interfaces\TagServiceInterface;
use App\Models\Tag;
use App\Http\Requests\CreateTagRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TagController extends Controller
{
    private $tagService;

    public function __construct(TagServiceInterface $tagService)
    {
        $this->tagService = $tagService;
    }

    public function create(CreateTagRequest $request)
    {
        $this->patchRequestEntityType($request);

        $createTagDTO = $request->createDTO();

        return $this->handleServiceCall(function () use ($createTagDTO) {
            return $this->tagService->create($createTagDTO);
        });
    }

    public function delete(Tag $tag)
    {
        return $this->handleServiceCall(function () use ($tag) {
            return $this->tagService->delete($tag);
        });
    }
}

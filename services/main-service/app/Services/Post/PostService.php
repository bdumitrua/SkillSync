<?php

namespace App\Services\Post;

use App\Services\Post\Interfaces\PostServiceInterface;

class PostService implements PostServiceInterface
{
    public function getPostsByUserId(int $id): ?array
    {
        return [];
    }
}

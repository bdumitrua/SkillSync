<?php

namespace App\Services\Post\Interfaces;

interface PostServiceInterface
{
    /**
     * @param int $id
     * 
     * @return array|null
     */
    public function getPostsByUserId(int $id): ?array;
}

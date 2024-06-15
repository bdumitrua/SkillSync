<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Services\Interfaces\LikeServiceInterface;
use App\Models\User;
use App\Models\Post;
use App\Models\Like;
use App\Http\Requests\LikeRequest;
use App\DTO\LikeDTO;

class LikeController extends Controller
{
    private $likeService;

    public function __construct(LikeServiceInterface $likeService)
    {
        $this->likeService = $likeService;
    }

    public function user(User $user)
    {
        return $this->handleServiceCall(function () use ($user) {
            return $this->likeService->user($user);
        });
    }

    public function post(Post $post)
    {
        return $this->handleServiceCall(function () use ($post) {
            return $this->likeService->post($post);
        });
    }

    public function create(LikeRequest $request)
    {
        $this->patchRequestEntityType($request, 'likeableType');
        $likeDTO = $this->createLikeDTO($request);

        return $this->handleServiceCall(function () use ($likeDTO) {
            return $this->likeService->create($likeDTO);
        });
    }

    public function delete(LikeRequest $request)
    {
        $this->patchRequestEntityType($request, 'likeableType');
        $likeDTO = $this->createLikeDTO($request);

        return $this->handleServiceCall(function () use ($likeDTO) {
            return $this->likeService->delete($likeDTO);
        });
    }

    protected function createLikeDTO(LikeRequest $request): LikeDTO
    {
        return (new LikeDTO())
            ->setUserId(Auth::id())
            ->setLikeableType($request->likeableType)
            ->setLikeableId($request->likeableId);
    }
}

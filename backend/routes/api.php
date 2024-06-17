<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\Authenticate;

include __DIR__ . "/auth.php";
include __DIR__ . "/message.php";
include __DIR__ . "/post.php";
include __DIR__ . "/team.php";
include __DIR__ . "/user.php";
include __DIR__ . "/project.php";
include __DIR__ . "/subscription.php";
include __DIR__ . "/like.php";
include __DIR__ . "/tag.php";


Route::withoutMiddleware([Authenticate::class . ':api'])->get('ping', function () {
    $currentTime = now();

    return response()->json([
        'message' => 'pong',
        'time' => $currentTime,
    ]);
});

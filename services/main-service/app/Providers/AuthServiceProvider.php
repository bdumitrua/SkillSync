<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Policies\UserPolicy;
use App\Policies\TeamPolicy;
use App\Policies\TeamApplicationPolicy;
use App\Policies\TagPolicy;
use App\Policies\PostPolicy;
use App\Policies\PostCommentPolicy;
use App\Models\User;
use App\Models\TeamApplication;
use App\Models\Team;
use App\Models\Tag;
use App\Models\PostComment;
use App\Models\Post;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
    }
}

<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Http\Request;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Cache\RateLimiting\Limit;
use App\Repositories\User\Interfaces\UserRepositoryInterface;
use App\Repositories\Team\Interfaces\TeamVacancyRepositoryInterface;
use App\Repositories\Team\Interfaces\TeamRepositoryInterface;
use App\Repositories\Project\Interfaces\ProjectRepositoryInterface;
use App\Exceptions\NotFoundException;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        parent::boot();

        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60 * 60)->by($request->user()?->id ?: $request->ip());
        });

        $bindings = [
            'user' => UserRepositoryInterface::class,
            'team' => TeamRepositoryInterface::class,
            'teamVacancy' => TeamVacancyRepositoryInterface::class,
            'project' => ProjectRepositoryInterface::class,
        ];

        foreach ($bindings as $key => $repository) {
            $this->bindRouteModel($key, $repository);
        }
    }

    /**
     * Helper function to bind route models.
     *
     * @param string $key
     * @param string $repository
     */
    protected function bindRouteModel(string $key, string $repository): void
    {
        Route::bind($key, function ($value) use ($key, $repository) {
            $id = (int)$value;
            $entity = app($repository)->getById($id);

            if (!$entity) {
                throw new NotFoundException(ucfirst($key));
            }

            return $entity;
        });
    }
}

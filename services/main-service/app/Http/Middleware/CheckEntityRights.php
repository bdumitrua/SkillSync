<?php

namespace App\Http\Middleware;

use App\Exceptions\AccessDeniedException;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckEntityRights
{
    public function handle(Request $request, Closure $next, string $entityName): Response
    {
        $entity = $request->route($entityName);

        if ($entity->user_id !== Auth::id()) {
            throw new AccessDeniedException();
        }

        return $next($request);
    }
}

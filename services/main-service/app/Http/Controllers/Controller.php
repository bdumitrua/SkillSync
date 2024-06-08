<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    /**
     * @param mixed $response
     * 
     * @return JsonResponse
     */
    private function responseToJSON($response): JsonResponse
    {
        if (empty($response)) {
            return response()->json(null, 200);
        }

        if ($response instanceof JsonResource) {
            return response()->json($response);
        }

        if ($response instanceof Response) {
            $code = $response->getStatusCode();
            $content = empty($response->getContent()) ? null : $response->getContent();

            return response()->json($content, $code);
        }

        return response()->json(null, 200);
    }

    /**
     * @param callable $serviceFunction
     * 
     * @return JsonResponse
     */
    protected function handleServiceCall(callable $serviceFunction): JsonResponse
    {
        $response = $serviceFunction();
        return $this->responseToJSON($response);
    }

    /**
     * @param Request $request
     * 
     * @return void
     */
    protected function patchRequestEntityType(Request &$request): void
    {
        Log::debug('Patching request entityType', ['request' => $request->toArray()]);
        $entitiesPath = config('entities');

        $request->merge([
            'entityType' => $entitiesPath[$request->entityType]
        ]);
        Log::debug('Patched request entityType', ['request' => $request->toArray()]);
    }
}

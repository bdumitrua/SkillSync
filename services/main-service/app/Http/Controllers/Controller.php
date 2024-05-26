<?php

namespace App\Http\Controllers;

use Illuminate\Auth\Access\Response as AccessResponse;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

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
}

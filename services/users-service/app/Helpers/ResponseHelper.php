<?php

namespace App\Helpers;

use Illuminate\Http\Response;

class ResponseHelper
{
    public static function successResponse(): Response
    {
        return response(null, Response::HTTP_OK);
    }

    public static function noContent(): Response
    {
        return response(null, Response::HTTP_NO_CONTENT);
    }

    public static function badRequest(): Response
    {
        return response(null, Response::HTTP_BAD_REQUEST);
    }

    public static function forbiddenRequest(): Response
    {
        return response(null, Response::HTTP_FORBIDDEN);
    }

    public static function internalError(): Response
    {
        return response(null, Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}

<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class AccessDeniedException extends HttpException
{
    protected $code = Response::HTTP_FORBIDDEN;

    public function __construct(string $message = "Access denied")
    {
        parent::__construct($this->code, $message);
    }
}

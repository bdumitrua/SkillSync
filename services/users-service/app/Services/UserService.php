<?php

namespace App\Services;

use App\Exceptions\CodeNotConfirmedException;
use App\Exceptions\IncorrectCodeException;
use App\Exceptions\InvalidCredetialsException;
use App\Exceptions\InvalidTokenException;
use App\Exceptions\NotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UserService
{
    public function index()
    {
        // 
    }
}

<?php

namespace App\Services;

use App\Exceptions\CodeNotConfirmedException;
use App\Exceptions\IncorrectCodeException;
use App\Exceptions\InvalidCredetialsException;
use App\Exceptions\InvalidTokenException;
use App\Exceptions\NotFoundException;
use App\Http\Requests\Auth\AuthConfirmCodeRequest;
use App\Http\Requests\Auth\CheckEmailRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\PasswordRequest;
use App\Http\Requests\User\CreateUserRequest;
use App\Http\Resources\Auth\AuthTokenResource;
use App\Http\Resources\Auth\PasswordResetCodeResource;
use App\Http\Resources\Auth\PasswordResetConfirmedResource;
use App\Models\AuthReset;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;

class AuthService
{
    /**
     * @param CreateUserRequest $request
     * 
     * @return JsonResource
     */
    public function register(CreateUserRequest $request): JsonResource
    {
        User::create([
            'first_name' => $request->firstName,
            'last_name' => $request->lastName,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'birthdate' => $request->birthdate,
        ]);

        $token = auth()->attempt([
            'email' => $request->email,
            'password' => $request->password
        ]);

        return new AuthTokenResource($token);
    }

    /**
     * @param CheckEmailRequest $request
     * 
     * @return JsonResource
     */
    public function resetCheck(CheckEmailRequest $request): JsonResource
    {
        $user = User::where('email', $request->email)->first();
        if (empty($user)) {
            throw new NotFoundException('Account');
        }

        $resetCode = $this->createUniqueCode();
        $authReset = AuthReset::create([
            'code' => $resetCode,
            'user_id' => $user->id,
        ]);

        return new PasswordResetCodeResource($authReset->id);
    }

    /**
     * @param AuthReset $authReset
     * @param AuthConfirmCodeRequest $request
     * 
     * @return JsonResource
     */
    public function resetConfirm(AuthReset $authReset, AuthConfirmCodeRequest $request): JsonResource
    {
        $resetId = $authReset->id;
        if ($request->code !== $authReset->code) {
            throw new IncorrectCodeException();
        }

        $authReset->confirmed = true;
        $authReset->save();

        return new PasswordResetConfirmedResource($resetId);
    }

    /**
     * @param AuthReset $authReset
     * @param PasswordRequest $request
     * 
     * @return void
     */
    public function resetEnd(AuthReset $authReset, PasswordRequest $request): void
    {
        if (empty($authReset->confirmed)) {
            throw new CodeNotConfirmedException();
        }

        $user = $authReset->user;
        $user->password = Hash::make($request->password);
        $user->token_invalid_before = now();
        $user->save();
        Log::info('Changed user password', ['user_id' => $user->id]);

        AuthReset::where('user_id', $user->id)->delete();
        Log::info('Deleted all reset password data after succesfull reset', ['user_id' => $user->id]);
    }

    /**
     * @param LoginRequest $request
     * 
     * @return JsonResource
     */
    public function login(LoginRequest $request): JsonResource
    {
        $credentials = $request->only('email', 'password');

        if (!$token = auth()->attempt($credentials)) {
            throw new InvalidCredetialsException();
        }

        Log::info('Authorized user by email and password', ['email' => $request->email, 'ip' => $request->ip()]);
        return new AuthTokenResource($token);
    }

    /**
     * @param Request $request
     * 
     * @return void
     */
    public function logout(Request $request): void
    {
        Auth::logout();
        Log::info('User exited from account', ['user' => Auth::user(), 'ip' => $request->ip()]);
    }

    /**
     * @return JsonResource
     */
    public function refresh(): JsonResource
    {
        try {
            $newToken = new AuthTokenResource(Auth::refresh());
            return $newToken;
        } catch (TokenBlacklistedException $e) {
            // From 502 to 403
            throw new InvalidTokenException();
        }
    }

    /**
     * @return string
     */
    protected function createUniqueCode(): string
    {
        return '11111';
    }
}

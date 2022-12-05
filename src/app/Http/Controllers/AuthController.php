<?php

namespace App\Http\Controllers;

use App\DataTransferObject\UserLoginData;
use App\Models\User;
use App\Traits\ApiResponses;
use App\DataTransferObject\UserData;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    use ApiResponses;

    /**
     * @param UserData $data
     * @return JsonResponse
     */
    public function register(UserData $data): JsonResponse
    {
        $user = User::create($data->all());
        $token = $user->createToken('auth_token')->plainTextToken;
        return $this->successfulResponse(['user' => $user, 'token' => $token, 'token_type' => 'bearer']);

    }

    /**
     * @param UserLoginData $loginData
     * @return JsonResponse
     * @throws AuthenticationException
     */
    public function login(UserLoginData $loginData): JsonResponse
    {
        if (Auth::attempt($loginData->all())) {
            $token = auth()->user()->createToken('auth_token')->plainTextToken;
            return $this->successfulResponse(['user' => auth()->user(), 'token' => $token, 'token_type' => 'bearer']);
        }

        throw new AuthenticationException('Unauthorized');
    }

    /**
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        auth()->user()->tokens()->delete();
        return $this->successfulResponse(['ok']);
    }
}

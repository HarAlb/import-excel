<?php

namespace App\Http\Api\V1\Auth;

use App\Http\Api\Base\Controller\ApiController;
use App\Http\Api\V1\Auth\Requests\LoginRequest;
use App\Http\Api\V1\Auth\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class AuthController extends ApiController
{
    public function login(LoginRequest $request): JsonResponse
    {
        if (!auth()->guard('web')->attempt($request->validated())) {
            return $this->validationError([
                'message' => [
                    'Login failed for user'
                ]
            ]);
        }

        $user = User::where('email', $request->email)->limit(1)->first();

        return $this->successResponse([
            'access_token' => $user->createToken('api_auth_token', ['user'])->accessToken,
            'token_type' => 'Bearer',
            'user' => new UserResource($user)
        ]);
    }
}

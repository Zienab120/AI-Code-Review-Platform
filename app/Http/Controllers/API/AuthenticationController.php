<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Authentication\LoginRequest;
use App\Http\Requests\Authentication\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Services\AuthService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class AuthenticationController extends Controller
{
    use ApiResponseTrait;
    public function __construct(protected AuthService $authService) {}

    public function register(RegisterRequest $request)
    {
        $data = $request->validated();
        $result = $this->authService->register($data);
        return $this->successResponse(['user' => new UserResource($result['user']), 'token' => $result['token']], 'Welcome! You have successfully registered.', 201);
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();
        $result = $this->authService->login($credentials);
        return $this->successResponse(['user' => new UserResource($result['user']), 'token' => $result['token']], 'You have successfully logged in.', 200);
    }

    public function me(Request $request)
    {
        $user = $request->user();
        return $this->successResponse(['user' => new UserResource($user)], 'User retrieved successfully.', 200);
    }

    public function logout(Request $request)
    {
        $user = $request->user();
        $this->authService->logout($user);
        return $this->successResponse(null, 'You have successfully logged out.', 200);
    }
}

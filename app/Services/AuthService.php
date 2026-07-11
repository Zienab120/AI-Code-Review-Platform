<?php

namespace App\Services;

use App\Models\User;

class AuthService
{
    public function register(array $data): array
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
        $token = $this->generateToken($user);
        return [
            'user' => $user,
            'token' => $token,
        ];
    }

    public function login(array $credentials): ?array
    {
        $user = User::where('email', $credentials['email'])->first();
        if ($user && password_verify($credentials['password'], $user->password)) {
            $token = $this->generateToken($user);
            return [
                'user' => $user,
                'token' => $token,
            ];
        } else throw new \Exception('Invalid credentials');
    }

    public function logout(User $user): void
    {
        $user->tokens()->delete();
    }

    private function generateToken(User $user): string
    {
        return $user->createToken('auth_token')->plainTextToken;
    }
}

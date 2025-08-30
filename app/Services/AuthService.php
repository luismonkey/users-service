<?php

namespace App\Services;

use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Validation\ValidationException;

class AuthService
{
    public function __construct(private UserRepository $users) {}

    public function register(array $data): array
    {
        $data['password'] = Hash::make($data['password']);
        $user = $this->users->create($data);

        $token = JWTAuth::fromUser($user);

        return compact('user', 'token');
    }

    public function login(array $credentials): array
    {
        if (!$token = JWTAuth::attempt($credentials)) {
            throw ValidationException::withMessages([
                'email' => ['Credenciales inválidas.'],
            ]);
        }

        return [
            'user' => auth()->user(),
            'token' => $token,
        ];
    }

    public function logout(): void
    {
        JWTAuth::invalidate(JWTAuth::getToken());
    }

    public function me(): array
    {
        return ['user' => auth()->user()];
    }
}

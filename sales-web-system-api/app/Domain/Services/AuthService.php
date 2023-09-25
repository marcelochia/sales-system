<?php

namespace App\Domain\Services;

use App\Helpers\Jwt;
use App\Models\User;
use RuntimeException;

class AuthService
{
    private User $user;

    public function validateUserAuthorization(string $userEmail, string $userPassword): bool
    {
        $user = User::where('email', $userEmail)->first();

        if (is_null($user)) {
            return false;
        }

        if (!password_verify($userPassword, $user->password)) {
            return false;
        }

        $this->user = $user;

        return true;
    }

    /** @throws RuntimeException se o usuário não foi validado */
    public function generateToken(): array
    {
        if (!isset($this->user)) {
            throw new RuntimeException('O usuário não foi validado.');
        }

        $token = Jwt::createToken($this->user);

        return [
            'token' => $token,
            'user' => $this->user->toArray()
        ];
    }
}

<?php

namespace App\Domain\ValueObjects;

readonly class Email
{
    /** @throws \InvalidArgumentException se o email for inválido */
    public function __construct(public string $value) {
        if (!self::isValid($value)) {
            throw new \InvalidArgumentException('O email não é valido.');
        }
    }

    public static function isValid(string $email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }
}

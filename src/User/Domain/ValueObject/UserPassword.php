<?php

declare(strict_types=1);

namespace Minascafe\User\Domain\ValueObject;

use Minascafe\Shared\Domain\ValueObject\StringValue;
use Minascafe\User\Domain\Adapter\HashPasswordAdapterInterface;

final class UserPassword extends StringValue implements HashPasswordAdapterInterface
{
    public static function generate(string $password): string
    {
        return password_hash($password, \PASSWORD_BCRYPT, ['cost' => 12]);
    }

    public static function verify(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }
}

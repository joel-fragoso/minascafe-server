<?php

declare(strict_types=1);

namespace Minascafe\User\Domain\Adapter;

interface HashPasswordAdapterInterface
{
    public static function generate(string $password): string;

    public static function verify(string $password, string $hash): bool;
}

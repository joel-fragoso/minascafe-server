<?php

declare(strict_types=1);

namespace Minascafe\Shared\Domain\Adapter;

interface UuidAdapterInterface
{
    public static function generate(): string;

    public static function isValid(string $uuid): bool;
}

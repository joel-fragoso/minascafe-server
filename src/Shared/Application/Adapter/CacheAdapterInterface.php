<?php

declare(strict_types=1);

namespace Minascafe\Shared\Application\Adapter;

interface CacheAdapterInterface
{
    public function save(string $key, mixed $value): void;

    public function recover(string $key): mixed;

    public function delete(string $key): void;
}

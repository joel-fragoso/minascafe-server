<?php

declare(strict_types=1);

namespace Minascafe\Shared\Application\Adapter;

interface StorageAdapterInterface
{
    public function save(mixed $file): string;

    public function delete(string $file): void;
}

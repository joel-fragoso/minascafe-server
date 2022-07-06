<?php

declare(strict_types=1);

namespace Minascafe\Shared\Infrastructure\Adapter;

use Minascafe\Shared\Application\Adapter\CacheAdapterInterface;

final class InMemoryCacheAdapter implements CacheAdapterInterface
{
    /**
     * @var array<string, mixed>
     */
    private array $data = [];

    public function save(string $key, mixed $value): void
    {
        $this->data[$key] = $value;
    }

    public function recover(string $key): mixed
    {
        return $this->data[$key] ?? null;
    }

    public function delete(string $key): void
    {
        unset($this->data[$key]);
    }
}

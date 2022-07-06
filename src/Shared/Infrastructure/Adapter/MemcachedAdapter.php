<?php

declare(strict_types=1);

namespace Minascafe\Shared\Infrastructure\Adapter;

use Memcache;
use Minascafe\Shared\Application\Adapter\CacheAdapterInterface;
use Psr\Container\ContainerInterface;

final class MemcachedAdapter implements CacheAdapterInterface
{
    private Memcache $client;

    public function __construct(private ContainerInterface $container)
    {
        $settings = $container->get('settings')['memcached'];

        $this->client = new Memcache();
        $this->client->connect($settings['host'], (int) $settings['port']);
    }

    public function save(string $key, mixed $value): void
    {
        $settings = $this->container->get('settings')['memcached'];

        $this->client->set($key, $value, 0, (int) $settings['expire']);
    }

    public function recover(string $key): mixed
    {
        $data = $this->client->get($key);

        if (!$data) {
            return null;
        }

        return $data;
    }

    public function delete(string $key): void
    {
        $this->client->delete($key);
    }
}

<?php

declare(strict_types=1);

namespace Minascafe\Shared\Infrastructure\Adapter;

use Minascafe\Shared\Application\Adapter\CacheAdapterInterface;
use Predis\Client;
use Predis\ClientInterface;
use Psr\Container\ContainerInterface;

final class RedisCacheAdapter implements CacheAdapterInterface
{
    private ClientInterface $client;

    public function __construct(private ContainerInterface $container)
    {
        $settings = $container->get('settings')['redis'];

        $this->client = new Client([
            'host' => $settings['host'],
            'port' => (int) $settings['port'],
        ]);
    }

    public function save(string $key, mixed $value): void
    {
        $settings = $this->container->get('settings')['redis'];

        $this->client->set($key, serialize($value));
        $this->client->expire($key, $settings['expire']);
    }

    public function recover(string $key): mixed
    {
        $data = $this->client->get($key);

        if (!$data) {
            return null;
        }

        return unserialize($data);
    }

    public function delete(string $key): void
    {
        $this->client->del($key);
    }
}

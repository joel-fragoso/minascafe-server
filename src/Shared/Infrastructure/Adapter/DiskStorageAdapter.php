<?php

declare(strict_types=1);

namespace Minascafe\Shared\Infrastructure\Adapter;

use Exception;
use Minascafe\Shared\Application\Adapter\StorageAdapterInterface;
use Psr\Container\ContainerInterface;

final class DiskStorageAdapter implements StorageAdapterInterface
{
    public function __construct(private ContainerInterface $container)
    {
    }

    public function save(mixed $file): string
    {
        $settings = $this->container->get('settings')['slim']['upload'];

        if (!is_dir($settings['path']) || !is_writable($settings['path'])) {
            throw new Exception("O diretório '{$settings['path']}' não existe e/ou não tem permissão de escrita");
        }

        $basename = bin2hex(random_bytes(10));
        $extention = pathinfo($file->getClientFilename(), \PATHINFO_EXTENSION);
        $filename = sprintf('%s.%0.10s', $basename, $extention);

        if (!move_uploaded_file($file->getFilePath(), "{$settings['path']}/{$filename}")) {
            throw new Exception("Erro ao fazer upload do arquivo '{$filename}'");
        }

        return $filename;
    }

    public function delete(string $filename): void
    {
        $settings = $this->container->get('settings')['slim']['upload'];

        $filePath = "{$settings['path']}/{$filename}";

        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }
}

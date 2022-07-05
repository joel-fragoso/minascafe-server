<?php

declare(strict_types=1);

namespace Minascafe\User\Infrastructure\Adapter;

use Minascafe\User\Application\Adapter\TemplateAdapterInterface;
use Psr\Container\ContainerInterface;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

final class TwigAdapter implements TemplateAdapterInterface
{
    public function __construct(private ContainerInterface $container)
    {
    }

    public function render(string $name, array $context = []): string
    {
        $settings = $this->container->get('settings')['twig'];

        $loader = new FilesystemLoader($settings['path']);

        $twig = new Environment($loader, ['cache' => $settings['cache']]);

        return $twig->render($name, $context);
    }
}

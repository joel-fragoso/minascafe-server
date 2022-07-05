<?php

declare(strict_types=1);

namespace Minascafe\User\Application\Adapter;

interface TemplateAdapterInterface
{
    /**
     * @param array<string, mixed> $context
     */
    public function render(string $name, array $context = []): string;
}

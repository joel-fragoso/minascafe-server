<?php

declare(strict_types=1);

namespace Minascafe\User\Application\Adapter;

interface MailAdapterInterface
{
    /**
     * @param array<string, mixed> $to
     */
    public function send(array $to, string $subject, string $template): void;
}

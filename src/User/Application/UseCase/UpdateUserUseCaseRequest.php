<?php

declare(strict_types=1);

namespace Minascafe\User\Application\UseCase;

final class UpdateUserUseCaseRequest
{
    public function __construct(
        private string $userId,
        private ?string $name = null,
        private ?bool $active = null
    ) {
    }

    public function userId(): string
    {
        return $this->userId;
    }

    public function name(): ?string
    {
        return $this->name;
    }

    public function isActive(): ?bool
    {
        return $this->active;
    }
}

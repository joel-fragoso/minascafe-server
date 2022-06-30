<?php

declare(strict_types=1);

namespace Minascafe\User\Application\UseCase;

final class CreateUserUseCaseRequest
{
    public function __construct(
        private string $name,
        private string $email,
        private string $password,
        private ?bool $active = null
    ) {
    }

    public function name(): string
    {
        return $this->name;
    }

    public function email(): string
    {
        return $this->email;
    }

    public function password(): string
    {
        return $this->password;
    }

    public function isActive(): ?bool
    {
        return $this->active;
    }
}

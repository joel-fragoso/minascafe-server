<?php

declare(strict_types=1);

namespace Minascafe\User\Application\UseCase;

use DateTimeInterface;
use JsonSerializable;

final class CreateUserUseCaseResponse implements JsonSerializable
{
    public function __construct(
        private string $userId,
        private string $name,
        private string $email,
        private string|null $avatar,
        private bool $active,
        private DateTimeInterface $createdAt,
        private ?DateTimeInterface $updatedAt
    ) {
    }

    public function userId(): string
    {
        return $this->userId;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function email(): string
    {
        return $this->email;
    }

    public function avatar(): string|null
    {
        return $this->avatar;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function createdAt(): DateTimeInterface
    {
        return $this->createdAt;
    }

    public function updatedAt(): ?DateTimeInterface
    {
        return $this->updatedAt;
    }

    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->userId(),
            'name' => $this->name(),
            'email' => $this->email(),
            'avatar' => $this->avatar(),
            'active' => $this->isActive(),
            'createdAt' => $this->createdAt(),
            'updatedAt' => $this->updatedAt(),
        ];
    }
}

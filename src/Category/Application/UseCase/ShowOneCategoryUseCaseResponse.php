<?php

declare(strict_types=1);

namespace Minascafe\Category\Application\UseCase;

use DateTimeInterface;
use JsonSerializable;

final class ShowOneCategoryUseCaseResponse implements JsonSerializable
{
    public function __construct(
        private string $categoryId,
        private string $name,
        private string $icon,
        private bool $active,
        private ?DateTimeInterface $updatedAt
    ) {
    }

    public function categoryId(): string
    {
        return $this->categoryId;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function icon(): string
    {
        return $this->icon;
    }

    public function isActive(): bool
    {
        return $this->active;
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
            'id' => $this->categoryId(),
            'name' => $this->name(),
            'icon' => $this->icon(),
            'active' => $this->isActive(),
            'updatedAt' => $this->updatedAt(),
        ];
    }
}

<?php

declare(strict_types=1);

namespace Minascafe\Product\Application\UseCase;

use DateTimeImmutable;
use DateTimeInterface;
use JsonSerializable;
use Minascafe\Category\Domain\Entity\Category;

final class ShowOneProductUseCaseResponse implements JsonSerializable
{
    public function __construct(
        private string $productId,
        private Category $category,
        private string $name,
        private float $price,
        private bool $active,
        private DateTimeInterface $createdAt
    ) {
    }

    public function productId(): string
    {
        return $this->productId;
    }

    public function category(): Category
    {
        return $this->category;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function price(): float
    {
        return $this->price;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function createdAt(): DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->productId(),
            'name' => $this->name(),
            'price' => $this->price(),
            'active' => $this->isActive(),
            'createdAt' => $this->createdAt()->format(DateTimeImmutable::ATOM),
            'category' => $this->category()->toArray(),
        ];
    }
}

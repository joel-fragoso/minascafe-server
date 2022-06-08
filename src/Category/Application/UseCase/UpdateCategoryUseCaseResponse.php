<?php

declare(strict_types=1);

namespace Minascafe\Category\Application\UseCase;

use JsonSerializable;

final class UpdateCategoryUseCaseResponse implements JsonSerializable
{
    public function __construct(private string $categoryId, private string $name)
    {
    }

    public function categoryId(): string
    {
        return $this->categoryId;
    }

    public function name(): string
    {
        return $this->name;
    }

    /**
     * @return array<string, int|string>
     */
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->categoryId(),
            'name' => $this->name(),
        ];
    }
}

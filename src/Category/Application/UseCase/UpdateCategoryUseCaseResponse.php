<?php

declare(strict_types=1);

namespace Minascafe\Category\Application\UseCase;

use JsonSerializable;

final class UpdateCategoryUseCaseResponse implements JsonSerializable
{
    public function __construct(private int $id, private string $name)
    {
    }

    public function id(): int
    {
        return $this->id;
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
            'id' => $this->id(),
            'name' => $this->name(),
        ];
    }
}

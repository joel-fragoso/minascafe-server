<?php

declare(strict_types=1);

namespace Minascafe\Category\Application\UseCase;

final class UpdateCategoryUseCaseRequest
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
}

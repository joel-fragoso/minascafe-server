<?php

declare(strict_types=1);

namespace Minascafe\Category\Application\UseCase;

use JsonSerializable;

final class CreateCategoryUseCaseResponse implements JsonSerializable
{
    public function __construct(private string $name)
    {
    }

    public function name(): string
    {
        return $this->name;
    }

    /**
     * @return array<string, string>
     */
    public function jsonSerialize(): array
    {
        return [
            'name' => $this->name(),
        ];
    }
}

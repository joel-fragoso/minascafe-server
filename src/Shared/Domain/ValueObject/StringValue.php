<?php

declare(strict_types=1);

namespace Minascafe\Shared\Domain\ValueObject;

class StringValue
{
    public function __construct(private string $value)
    {
    }

    public function value(): string
    {
        return $this->value;
    }
}

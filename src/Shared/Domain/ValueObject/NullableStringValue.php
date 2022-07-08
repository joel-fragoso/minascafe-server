<?php

declare(strict_types=1);

namespace Minascafe\Shared\Domain\ValueObject;

class NullableStringValue
{
    public function __construct(private string|null $value)
    {
    }

    public function value(): string|null
    {
        return $this->value;
    }
}

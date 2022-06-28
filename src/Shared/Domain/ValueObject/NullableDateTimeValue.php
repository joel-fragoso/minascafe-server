<?php

declare(strict_types=1);

namespace Minascafe\Shared\Domain\ValueObject;

use DateTimeInterface;

class NullableDateTimeValue
{
    public function __construct(private ?DateTimeInterface $value)
    {
    }

    public function value(): ?DateTimeInterface
    {
        return $this->value;
    }
}

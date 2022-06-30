<?php

declare(strict_types=1);

namespace Minascafe\Shared\Domain\ValueObject;

use Minascafe\Shared\Domain\Exception\InvalidEmailException;

class EmailValue
{
    /**
     * @throws InvalidEmailException
     */
    public function __construct(private string $value)
    {
        if (!$this->isValid($value)) {
            throw new InvalidEmailException("O email '{$value}' não é válido");
        }

        $this->value = $value;
    }

    public function value(): string
    {
        return $this->value;
    }

    private function isValid(string $value): bool
    {
        return (bool) filter_var($value, \FILTER_VALIDATE_EMAIL);
    }
}

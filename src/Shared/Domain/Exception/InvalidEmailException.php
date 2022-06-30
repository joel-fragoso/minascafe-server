<?php

declare(strict_types=1);

namespace Minascafe\Shared\Domain\Exception;

use Throwable;

final class InvalidEmailException extends DomainException
{
    public function __construct(string $message, int $code = 400, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}

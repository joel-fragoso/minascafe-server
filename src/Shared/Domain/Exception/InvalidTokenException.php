<?php

declare(strict_types=1);

namespace Minascafe\Shared\Domain\Exception;

use Throwable;

final class InvalidTokenException extends DomainException
{
    public function __construct(string $message, int $code = 401, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}

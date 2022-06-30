<?php

declare(strict_types=1);

namespace Minascafe\User\Domain\Exception;

use Minascafe\Shared\Domain\Exception\DomainException;
use Throwable;

final class UserNotFoundException extends DomainException
{
    public function __construct(string $message, int $code = 404, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}

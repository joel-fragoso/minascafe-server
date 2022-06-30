<?php

declare(strict_types=1);

namespace Minascafe\User\Application\UseCase;

final class DeleteUserUseCaseRequest
{
    public function __construct(private string $userId)
    {
    }

    public function userId(): string
    {
        return $this->userId;
    }
}

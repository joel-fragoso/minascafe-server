<?php

declare(strict_types=1);

namespace Minascafe\User\Application\UseCase;

final class ShowAllUsersUseCaseRequest
{
    public function __construct(
        private ?int $active = null,
        private ?string $order = null,
        private ?int $limit = null,
        private ?int $offset = null
    ) {
    }

    public function active(): ?int
    {
        return $this->active;
    }

    public function order(): ?string
    {
        return $this->order;
    }

    public function limit(): ?int
    {
        return $this->limit;
    }

    public function offset(): ?int
    {
        return $this->offset;
    }
}

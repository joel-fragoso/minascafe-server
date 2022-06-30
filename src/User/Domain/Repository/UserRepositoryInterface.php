<?php

declare(strict_types=1);

namespace Minascafe\User\Domain\Repository;

use Minascafe\User\Domain\Entity\User;
use Minascafe\User\Domain\ValueObject\UserEmail;
use Minascafe\User\Domain\ValueObject\UserId;

interface UserRepositoryInterface
{
    /**
     * @return User[]
     */
    public function findAll(
        ?int $active = null,
        ?string $order = null,
        ?int $limit = null,
        ?int $offset = null
    ): array;

    public function findById(UserId $userId): ?User;

    public function findByEmail(UserEmail $userEmail): ?User;

    public function create(User $user): void;

    public function update(User $user): void;

    public function delete(User $user): void;
}

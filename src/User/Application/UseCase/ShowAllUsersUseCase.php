<?php

declare(strict_types=1);

namespace Minascafe\User\Application\UseCase;

use Minascafe\Shared\Application\Adapter\CacheAdapterInterface;
use Minascafe\User\Domain\Repository\UserRepositoryInterface;

final class ShowAllUsersUseCase
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private CacheAdapterInterface $cacheAdapter,
    ) {
    }

    public function execute(
        ShowAllUsersUseCaseRequest $showAllUsersUseCaseRequest
    ): ShowAllUsersUseCaseResponse {
        $users = $this->cacheAdapter->recover('show-all-users');

        if (
            null !== $showAllUsersUseCaseRequest->active()
            || null !== $showAllUsersUseCaseRequest->order()
            || null !== $showAllUsersUseCaseRequest->limit()
            || null !== $showAllUsersUseCaseRequest->offset()
            || !$users
        ) {
            $users = $this->userRepository->findAll(
                $showAllUsersUseCaseRequest->active(),
                $showAllUsersUseCaseRequest->order(),
                $showAllUsersUseCaseRequest->limit(),
                $showAllUsersUseCaseRequest->offset(),
            );

            $this->cacheAdapter->save('show-all-users', $users);
        }

        return new ShowAllUsersUseCaseResponse($users);
    }
}

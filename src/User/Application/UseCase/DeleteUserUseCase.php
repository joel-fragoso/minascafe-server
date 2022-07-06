<?php

declare(strict_types=1);

namespace Minascafe\User\Application\UseCase;

use Minascafe\Shared\Application\Adapter\CacheAdapterInterface;
use Minascafe\User\Domain\Exception\UserNotFoundException;
use Minascafe\User\Domain\Repository\UserRepositoryInterface;
use Minascafe\User\Domain\ValueObject\UserId;

final class DeleteUserUseCase
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private CacheAdapterInterface $cacheAdapter,
    ) {
    }

    /**
     * @throws UserNotFoundException
     */
    public function execute(DeleteUserUseCaseRequest $deleteUserUseCaseRequest): void
    {
        $userId = $deleteUserUseCaseRequest->userId();

        $findUser = $this->userRepository->findById(new UserId($userId));

        if (!$findUser) {
            throw new UserNotFoundException("O usuário '{$userId}' não foi encontrado");
        }

        $this->userRepository->delete($findUser);

        $this->cacheAdapter->delete('show-all-users');
    }
}

<?php

declare(strict_types=1);

namespace Minascafe\User\Application\UseCase;

use Minascafe\User\Domain\Exception\UserNotFoundException;
use Minascafe\User\Domain\Repository\UserRepositoryInterface;
use Minascafe\User\Domain\ValueObject\UserId;

final class ShowOneUserUseCase
{
    public function __construct(private UserRepositoryInterface $userRepository)
    {
    }

    /**
     * @throws UserNotFoundException
     */
    public function execute(ShowOneUserUseCaseRequest $showOneUserUseCaseRequest): ShowOneUserUseCaseResponse
    {
        $userId = $showOneUserUseCaseRequest->userId();

        $findUser = $this->userRepository->findById(new UserId($userId));

        if (!$findUser) {
            throw new UserNotFoundException("O usuário '{$userId}' não foi encontrado");
        }

        return new ShowOneUserUseCaseResponse(
            $findUser->id()->value(),
            $findUser->name()->value(),
            $findUser->email()->value(),
            $findUser->isActive()->value(),
            $findUser->createdAt()->value(),
            $findUser->updatedAt()->value()
        );
    }
}

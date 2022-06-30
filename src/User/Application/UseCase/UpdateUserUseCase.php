<?php

declare(strict_types=1);

namespace Minascafe\User\Application\UseCase;

use DateTimeImmutable;
use Minascafe\User\Domain\Entity\User;
use Minascafe\User\Domain\Exception\UserNotFoundException;
use Minascafe\User\Domain\Repository\UserRepositoryInterface;
use Minascafe\User\Domain\ValueObject\UserActive;
use Minascafe\User\Domain\ValueObject\UserCreatedAt;
use Minascafe\User\Domain\ValueObject\UserEmail;
use Minascafe\User\Domain\ValueObject\UserId;
use Minascafe\User\Domain\ValueObject\UserName;
use Minascafe\User\Domain\ValueObject\UserPassword;
use Minascafe\User\Domain\ValueObject\UserUpdatedAt;

final class UpdateUserUseCase
{
    public function __construct(private UserRepositoryInterface $userRepository)
    {
    }

    public function execute(UpdateUserUseCaseRequest $updateUserUseCaseRequest): UpdateUserUseCaseResponse
    {
        $userId = $updateUserUseCaseRequest->userId();

        $findUser = $this->userRepository->findById(new UserId($userId));

        if (!$findUser) {
            throw new UserNotFoundException("O usuário '{$userId}' não foi encontrado");
        }

        $user = User::create(
            new UserId($findUser->id()->value()),
            new UserName($updateUserUseCaseRequest->name() ?? $findUser->name()->value()),
            new UserEmail($findUser->email()->value()),
            new UserPassword($findUser->password()->value()),
            new UserActive($updateUserUseCaseRequest->isActive() ?? $findUser->isActive()->value()),
            new UserCreatedAt($findUser->createdAt()->value()),
            new UserUpdatedAt(new DateTimeImmutable())
        );

        $this->userRepository->update($user);

        return new UpdateUserUseCaseResponse(
            $user->id()->value(),
            $user->name()->value(),
            $user->email()->value(),
            $user->isActive()->value(),
            $user->createdAt()->value(),
            $user->updatedAt()->value()
        );
    }
}

<?php

declare(strict_types=1);

namespace Minascafe\User\Application\UseCase;

use DateTimeImmutable;
use Minascafe\Shared\Application\Adapter\CacheAdapterInterface;
use Minascafe\User\Domain\Entity\User;
use Minascafe\User\Domain\Exception\DuplicatedUserException;
use Minascafe\User\Domain\Repository\UserRepositoryInterface;
use Minascafe\User\Domain\ValueObject\UserActive;
use Minascafe\User\Domain\ValueObject\UserCreatedAt;
use Minascafe\User\Domain\ValueObject\UserEmail;
use Minascafe\User\Domain\ValueObject\UserId;
use Minascafe\User\Domain\ValueObject\UserName;
use Minascafe\User\Domain\ValueObject\UserPassword;
use Minascafe\User\Domain\ValueObject\UserUpdatedAt;

final class CreateUserUseCase
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private CacheAdapterInterface $cacheAdapter,
    ) {
    }

    public function execute(CreateUserUseCaseRequest $createUserUseCaseRequest): CreateUserUseCaseResponse
    {
        $userEmail = $createUserUseCaseRequest->email();

        $findUser = $this->userRepository->findByEmail(new UserEmail($userEmail));

        if ($findUser) {
            throw new DuplicatedUserException("O usuário '{$userEmail}' já existe");
        }

        $user = User::create(
            new UserId(UserId::generate()),
            new UserName($createUserUseCaseRequest->name()),
            new UserEmail($userEmail),
            new UserPassword(UserPassword::generate($createUserUseCaseRequest->password())),
            new UserActive(true),
            new UserCreatedAt(new DateTimeImmutable()),
            new UserUpdatedAt(null)
        );

        $this->userRepository->create($user);

        $this->cacheAdapter->delete('show-all-users');

        return new CreateUserUseCaseResponse(
            $user->id()->value(),
            $user->name()->value(),
            $user->email()->value(),
            $user->isActive()->value(),
            $user->createdAt()->value(),
            $user->updatedAt()->value()
        );
    }
}

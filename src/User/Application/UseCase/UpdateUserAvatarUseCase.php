<?php

declare(strict_types=1);

namespace Minascafe\User\Application\UseCase;

use DateTimeImmutable;
use Minascafe\Shared\Application\Adapter\StorageAdapterInterface;
use Minascafe\User\Domain\Entity\User;
use Minascafe\User\Domain\Exception\UserNotFoundException;
use Minascafe\User\Domain\Repository\UserRepositoryInterface;
use Minascafe\User\Domain\ValueObject\UserActive;
use Minascafe\User\Domain\ValueObject\UserAvatar;
use Minascafe\User\Domain\ValueObject\UserCreatedAt;
use Minascafe\User\Domain\ValueObject\UserEmail;
use Minascafe\User\Domain\ValueObject\UserId;
use Minascafe\User\Domain\ValueObject\UserName;
use Minascafe\User\Domain\ValueObject\UserPassword;
use Minascafe\User\Domain\ValueObject\UserUpdatedAt;

final class UpdateUserAvatarUseCase
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private StorageAdapterInterface $storageAdapter,
    ) {
    }

    public function execute(UpdateUserAvatarUseCaseRequest $updateUserAvatarUseCaseRequest): UpdateUserAvatarUseCaseResponse
    {
        $userId = $updateUserAvatarUseCaseRequest->userId();

        $findUser = $this->userRepository->findById(new UserId($userId));

        if (!$findUser) {
            throw new UserNotFoundException("O usuário '{$userId}' não foi encontrado");
        }

        if ($findUser->avatar()->value()) {
            $this->storageAdapter->delete($findUser->avatar()->value());
        }

        $uploadedFile = $this->storageAdapter->save($updateUserAvatarUseCaseRequest->avatar());

        $user = User::create(
            new UserId($findUser->id()->value()),
            new UserName($findUser->name()->value()),
            new UserEmail($findUser->email()->value()),
            new UserPassword($findUser->password()->value()),
            new UserAvatar($uploadedFile),
            new UserActive($findUser->isActive()->value()),
            new UserCreatedAt($findUser->createdAt()->value()),
            new UserUpdatedAt(new DateTimeImmutable()),
        );

        $this->userRepository->update($user);

        return new UpdateUserAvatarUseCaseResponse(
            $user->id()->value(),
            $user->name()->value(),
            $user->email()->value(),
            $user->avatar()->value(),
            "{$_ENV['APP_SERVER_URL']}/arquivos/{$user->avatar()->value()}",
            $user->isActive()->value(),
            $user->createdAt()->value(),
            $user->updatedAt()->value(),
        );
    }
}

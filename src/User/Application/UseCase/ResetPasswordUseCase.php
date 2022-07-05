<?php

declare(strict_types=1);

namespace Minascafe\User\Application\UseCase;

use DateTimeImmutable;
use Minascafe\Shared\Domain\Exception\InvalidTokenException;
use Minascafe\User\Domain\Entity\User;
use Minascafe\User\Domain\Exception\UserNotFoundException;
use Minascafe\User\Domain\Exception\UserTokenNotFoundException;
use Minascafe\User\Domain\Repository\UserRepositoryInterface;
use Minascafe\User\Domain\Repository\UserTokenRepositoryInterface;
use Minascafe\User\Domain\ValueObject\UserActive;
use Minascafe\User\Domain\ValueObject\UserCreatedAt;
use Minascafe\User\Domain\ValueObject\UserEmail;
use Minascafe\User\Domain\ValueObject\UserId;
use Minascafe\User\Domain\ValueObject\UserName;
use Minascafe\User\Domain\ValueObject\UserPassword;
use Minascafe\User\Domain\ValueObject\UserTokenToken;
use Minascafe\User\Domain\ValueObject\UserUpdatedAt;

final class ResetPasswordUseCase
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private UserTokenRepositoryInterface $userTokenRepository
    ) {
    }

    /**
     * @throws UserTokenNotFoundException
     * @throws UserNotFoundException
     * @throws InvalidTokenException
     */
    public function execute(ResetPasswordUseCaseRequest $resetPasswordUseCaseRequest): void
    {
        $userTokenToken = $resetPasswordUseCaseRequest->token();

        $findUserToken = $this->userTokenRepository->findUserTokenByToken(new UserTokenToken($userTokenToken));

        if (!$findUserToken) {
            throw new UserTokenNotFoundException("O token '{$userTokenToken}' não foi encontrado");
        }

        $userId = $findUserToken->user()->id()->value();

        $findUser = $this->userRepository->findById(new UserId($userId));

        if (!$findUser) {
            throw new UserNotFoundException("O usuário '{$userId}' não foi encontrado");
        }

        $user = User::create(
            new UserId($findUser->id()->value()),
            new UserName($findUser->name()->value()),
            new UserEmail($findUser->email()->value()),
            new UserPassword(UserPassword::generate($resetPasswordUseCaseRequest->password())),
            new UserActive($findUser->isActive()->value()),
            new UserCreatedAt($findUser->createdAt()->value()),
            new UserUpdatedAt(new DateTimeImmutable()),
        );

        $compareDate = (new DateTimeImmutable($findUserToken->createdAt()->value()->format('Y-m-d H:i:s')))
            ->modify('+15 minute');

        $currentDate = new DateTimeImmutable();

        if ($currentDate > $compareDate) {
            throw new InvalidTokenException('O token é inválido');
        }

        $this->userRepository->update($user);
    }
}

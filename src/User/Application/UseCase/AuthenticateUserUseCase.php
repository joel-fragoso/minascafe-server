<?php

declare(strict_types=1);

namespace Minascafe\User\Application\UseCase;

use Exception;
use Firebase\JWT\JWT;
use Minascafe\User\Domain\Repository\UserRepositoryInterface;
use Minascafe\User\Domain\ValueObject\UserEmail;
use Minascafe\User\Domain\ValueObject\UserPassword;

final class AuthenticateUserUseCase
{
    public function __construct(private UserRepositoryInterface $userRepository)
    {
    }

    public function execute(AuthenticateUserUseCaseRequest $authenticateUserUseCaseRequest): AuthenticateUserUseCaseResponse
    {
        $findUser = $this->userRepository->findByEmail(new UserEmail($authenticateUserUseCaseRequest->email()));

        if (
            !$findUser
            || !UserPassword::verify($authenticateUserUseCaseRequest->password(), $findUser->password()->value())
        ) {
            throw new Exception('E-mail e/ou senha inválidos', 401);
        }

        $payload = ['sub' => $findUser->id()->value(), 'exp' => strtotime('+1 day')];

        $token = JWT::encode($payload, $_ENV['APP_SECRET_KEY'], 'HS256');

        return new AuthenticateUserUseCaseResponse(
            $findUser,
            $token
        );
    }
}

<?php

declare(strict_types=1);

namespace Minascafe\User\Infrastructure\Http\Controller;

use Exception;
use Minascafe\Shared\Infrastructure\Http\Controller\BaseController;
use Minascafe\User\Application\UseCase\AuthenticateUserUseCase;
use Minascafe\User\Application\UseCase\AuthenticateUserUseCaseRequest;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;

final class SessionController extends BaseController
{
    public function __construct(
        private LoggerInterface $logger,
        private AuthenticateUserUseCase $authenticateUserUseCase
    ) {
    }

    public function create(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        try {
            $data = $request->getParsedBody();

            $email = $data['email'] ?? null;
            $password = $data['password'] ?? null;

            $authenticateUserUseCaseRequest = new AuthenticateUserUseCaseRequest($email, $password);

            $authenticateUserUseCaseResponse = $this->authenticateUserUseCase->execute(
                $authenticateUserUseCaseRequest
            );

            $payload = ['data' => $authenticateUserUseCaseResponse];

            return $this->jsonResponse($response, $payload);
        } catch (Exception $exception) {
            $payload = ['message' => $exception->getMessage()];

            $this->logger->error($exception->getMessage());

            return $this->jsonResponse($response, $payload, $exception->getCode());
        }
    }
}

<?php

declare(strict_types=1);

namespace Minascafe\User\Infrastructure\Http\Controller;

use Exception;
use Minascafe\Shared\Infrastructure\Http\Controller\BaseController;
use Minascafe\User\Application\UseCase\ResetPasswordUseCase;
use Minascafe\User\Application\UseCase\ResetPasswordUseCaseRequest;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Log\LoggerInterface;
use Selective\Validation\Exception\ValidationException;
use Selective\Validation\ValidationResult;

final class ResetPasswordController extends BaseController
{
    public function __construct(
        private LoggerInterface $logger,
        private ResetPasswordUseCase $resetPasswordUseCase,
    ) {
    }

    public function create(Request $request, Response $response): Response
    {
        try {
            $data = $request->getParsedBody();

            $token = $data['token'] ?? null;
            $password = $data['password'] ?? null;
            $passwordConfirmation = $data['passwordConfirmation'] ?? null;

            $validation = new ValidationResult();

            if (empty($token)) {
                $validation->addError('token', "O campo 'token' é obrigatório");
            }

            if (empty($password)) {
                $validation->addError('password', "O campo 'password' é obrigatório");
            }

            if ($password !== $passwordConfirmation) {
                $validation->addError('passwordConfirmation', 'As senhas não conferem');
            }

            if ($validation->fails()) {
                throw new ValidationException('Erro de validação', $validation);
            }

            $resetPasswordUseCaseRequest = new ResetPasswordUseCaseRequest($token, $password);

            $this->resetPasswordUseCase->execute($resetPasswordUseCaseRequest);

            return $this->jsonResponse($response, [], 204);
        } catch (ValidationException $exception) {
            $validationResult = $exception->getValidationResult();

            $messages = [];

            foreach ($validationResult->getErrors() as $error) {
                $messages[] = $error->getMessage();
            }

            $payload = [
                'error' => [
                    'code' => $exception->getCode(),
                    'message' => $messages,
                ],
            ];

            $this->logger->error('Erro de validação', $payload);

            return $this->jsonResponse($response, $payload, $exception->getCode());
        } catch (Exception $exception) {
            $payload = [
                'error' => [
                    'code' => $exception->getCode(),
                    'message' => $exception->getMessage(),
                ],
            ];

            $this->logger->error($exception->getMessage());

            return $this->jsonResponse($response, $payload, $exception->getCode());
        }
    }
}

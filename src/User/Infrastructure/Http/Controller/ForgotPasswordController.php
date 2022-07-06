<?php

declare(strict_types=1);

namespace Minascafe\User\Infrastructure\Http\Controller;

use Exception;
use Minascafe\Shared\Infrastructure\Http\Controller\BaseController;
use Minascafe\User\Application\UseCase\SendForgotPasswordUseCase;
use Minascafe\User\Application\UseCase\SendForgotPasswordUseCaseRequest;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Log\LoggerInterface;
use Selective\Validation\Exception\ValidationException;
use Selective\Validation\ValidationResult;

final class ForgotPasswordController extends BaseController
{
    public function __construct(
        private LoggerInterface $logger,
        private SendForgotPasswordUseCase $sendForgotPasswordUseCase,
    ) {
    }

    public function create(Request $request, Response $response): Response
    {
        try {
            $data = $request->getParsedBody();

            $email = $data['email'] ?? null;

            $validation = new ValidationResult();

            if (empty($email)) {
                $validation->addError('email', "O campo 'email' é obrigatório");
            }

            if ($validation->fails()) {
                throw new ValidationException('Erro de validação', $validation);
            }

            $sendForgotPasswordRequest = new SendForgotPasswordUseCaseRequest($email);

            $this->sendForgotPasswordUseCase->execute($sendForgotPasswordRequest);

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

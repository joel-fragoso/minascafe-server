<?php

declare(strict_types=1);

namespace Minascafe\User\Infrastructure\Http\Controller;

use Exception;
use Minascafe\Shared\Infrastructure\Http\Controller\BaseController;
use Minascafe\User\Application\UseCase\UpdateUserAvatarUseCase;
use Minascafe\User\Application\UseCase\UpdateUserAvatarUseCaseRequest;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;
use Selective\Validation\Exception\ValidationException;
use Selective\Validation\ValidationResult;

final class UserAvatarController extends BaseController
{
    public function __construct(
        private LoggerInterface $logger,
        private UpdateUserAvatarUseCase $updateUserAvatarUseCase,
    ) {
    }

    public function update(ServerRequestInterface $request, Response $response, string $id): Response
    {
        try {
            $uploadedFiles = $request->getUploadedFiles();

            $avatar = $uploadedFiles['avatar'] ?? null;

            $validation = new ValidationResult();

            if (null === $avatar || empty($avatar)) {
                $validation->addError('avatar', "O campo 'avatar' é obrigatório");
            }

            if ($validation->fails()) {
                throw new ValidationException('Erro de validação', $validation);
            }

            $updateUserAvatarUseCaseRequest = new UpdateUserAvatarUseCaseRequest($id, $avatar);

            $updateUserAvatarUseCaseResponse = $this->updateUserAvatarUseCase->execute($updateUserAvatarUseCaseRequest);

            $payload = ['data' => $updateUserAvatarUseCaseResponse];

            return $this->jsonResponse($response, $payload);
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

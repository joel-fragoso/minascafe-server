<?php

declare(strict_types=1);

namespace Minascafe\User\Infrastructure\Http\Controller;

use Exception;
use Minascafe\Shared\Infrastructure\Http\Controller\BaseController;
use Minascafe\User\Application\UseCase\CreateUserUseCase;
use Minascafe\User\Application\UseCase\CreateUserUseCaseRequest;
use Minascafe\User\Application\UseCase\DeleteUserUseCase;
use Minascafe\User\Application\UseCase\DeleteUserUseCaseRequest;
use Minascafe\User\Application\UseCase\ShowAllUsersUseCase;
use Minascafe\User\Application\UseCase\ShowAllUsersUseCaseRequest;
use Minascafe\User\Application\UseCase\ShowOneUserUseCase;
use Minascafe\User\Application\UseCase\ShowOneUserUseCaseRequest;
use Minascafe\User\Application\UseCase\UpdateUserUseCase;
use Minascafe\User\Application\UseCase\UpdateUserUseCaseRequest;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Log\LoggerInterface;
use Selective\Validation\Exception\ValidationException;
use Selective\Validation\ValidationResult;

final class UserController extends BaseController
{
    public function __construct(
        private LoggerInterface $logger,
        private ShowAllUsersUseCase $showAllUsersUseCase,
        private CreateUserUseCase $createUserUseCase,
        private ShowOneUserUseCase $showOneUserUseCase,
        private UpdateUserUseCase $updateUserUseCase,
        private DeleteUserUseCase $deleteUserUseCase
    ) {
    }

    public function index(Request $request, Response $response): Response
    {
        try {
            $queryParams = $request->getQueryParams();

            $active = isset($queryParams['active']) ? (int) $queryParams['active'] : null;
            $order = isset($queryParams['order']) ? $queryParams['order'] : null;
            $limit = isset($queryParams['limit']) ? (int) $queryParams['limit'] : null;
            $offset = isset($queryParams['offset']) ? (int) $queryParams['offset'] : null;

            $showAllUsersUseCaseRequest = new ShowAllUsersUseCaseRequest($active, $order, $limit, $offset);

            $showAllUsersUseCaseResponse = $this->showAllUsersUseCase->execute($showAllUsersUseCaseRequest);

            $payload = [
                'data' => $showAllUsersUseCaseResponse,
            ];

            return $this->jsonResponse($response, $payload);
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

    public function create(Request $request, Response $response): Response
    {
        try {
            $data = $request->getParsedBody();

            $name = $data['name'] ?? null;
            $email = $data['email'] ?? null;
            $password = $data['password'] ?? null;
            $active = $data['active'] ?? null;

            $validation = new ValidationResult();

            if (null === $name || empty($name)) {
                $validation->addError('name', "O campo 'name' é obrigatório");
            }

            if (null === $email || empty($email)) {
                $validation->addError('email', "O campo 'email' é obrigatório");
            }

            if (null === $password || empty($password)) {
                $validation->addError('password', "O campo 'password' é obrigatório");
            }

            if (null !== $active && '' === $active) {
                $validation->addError('active', "O campo 'active' é obrigatório");
            }

            if ($validation->fails()) {
                throw new ValidationException('Error validation', $validation);
            }

            $createUserUseCaseRequest = new CreateUserUseCaseRequest($name, $email, $password, $active);

            $createUserUseCaseResponse = $this->createUserUseCase->execute($createUserUseCaseRequest);

            $payload = [
                'data' => $createUserUseCaseResponse,
            ];

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

    public function show(Request $request, Response $response, string $id): Response
    {
        try {
            $showOneUserUseCaseRequest = new ShowOneUserUseCaseRequest($id);

            $showOneUserUseCaseResponse = $this->showOneUserUseCase->execute($showOneUserUseCaseRequest);

            $payload = [
                'data' => $showOneUserUseCaseResponse,
            ];

            return $this->jsonResponse($response, $payload);
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

    public function update(Request $request, Response $response, string $id): Response
    {
        try {
            $data = $request->getParsedBody();

            $name = $data['name'] ?? null;
            $active = $data['active'] ?? null;

            $validation = new ValidationResult();

            if (null !== $name && '' === $name) {
                $validation->addError('name', "O campo 'name' é obrigatório");
            }

            if (null !== $active && '' === $active) {
                $validation->addError('active', "O campo 'active' é obrigatório");
            }

            if ($validation->fails()) {
                throw new ValidationException('Error validation', $validation);
            }

            $updateUserUseCaseRequest = new UpdateUserUseCaseRequest($id, $name, $active);

            $updateUserUseCaseResponse = $this->updateUserUseCase->execute($updateUserUseCaseRequest);

            $payload = [
                'data' => $updateUserUseCaseResponse,
            ];

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

    public function destroy(Request $request, Response $response, string $id): Response
    {
        try {
            $deleteUserUseCaseRequest = new DeleteUserUseCaseRequest($id);

            $this->deleteUserUseCase->execute($deleteUserUseCaseRequest);

            return $this->jsonResponse($response, [], 204);
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

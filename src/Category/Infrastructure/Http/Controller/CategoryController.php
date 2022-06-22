<?php

declare(strict_types=1);

namespace Minascafe\Category\Infrastructure\Http\Controller;

use Exception;
use Minascafe\Category\Application\UseCase\CreateCategoryUseCase;
use Minascafe\Category\Application\UseCase\CreateCategoryUseCaseRequest;
use Minascafe\Category\Application\UseCase\DeleteCategoryUseCase;
use Minascafe\Category\Application\UseCase\DeleteCategoryUseCaseRequest;
use Minascafe\Category\Application\UseCase\ShowAllCategoriesUseCase;
use Minascafe\Category\Application\UseCase\ShowOneCategoryUseCase;
use Minascafe\Category\Application\UseCase\ShowOneCategoryUseCaseRequest;
use Minascafe\Category\Application\UseCase\UpdateCategoryUseCase;
use Minascafe\Category\Application\UseCase\UpdateCategoryUseCaseRequest;
use Minascafe\Shared\Infrastructure\Http\Controller\BaseController;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Selective\Validation\Exception\ValidationException;
use Selective\Validation\ValidationResult;

final class CategoryController extends BaseController
{
    public function __construct(
        private ShowAllCategoriesUseCase $showAllCategoriesUseCase,
        private CreateCategoryUseCase $createCategoryUseCase,
        private ShowOneCategoryUseCase $showOneCategoryUseCase,
        private UpdateCategoryUseCase $updateCategoryUseCase,
        private DeleteCategoryUseCase $deleteCategoryUseCase
    ) {
    }

    public function index(Request $request, Response $response): Response
    {
        try {
            $payload = [
                'data' => $this->showAllCategoriesUseCase->execute(),
            ];

            return $this->jsonResponse($response, $payload);
        } catch (Exception $exception) {
            $payload = [
                'error' => [
                    'code' => $exception->getCode(),
                    'message' => $exception->getMessage(),
                ],
            ];

            return $this->jsonResponse($response, $payload, $exception->getCode());
        }
    }

    public function create(Request $request, Response $response): Response
    {
        try {
            ['name' => $name, 'icon' => $icon] = $request->getParsedBody();

            $validation = new ValidationResult();

            if (empty($name)) {
                $validation->addError('name', "O campo 'name' é obrigatório");
            }

            if (empty($icon)) {
                $validation->addError('icon', "O campo 'icon' é obrigatório");
            }

            if ($validation->fails()) {
                throw new ValidationException('Validation error', $validation);
            }

            $createCategoryUseCaseRequest = new CreateCategoryUseCaseRequest($name, $icon);

            $createCategoryUseCaseResponse = $this->createCategoryUseCase->execute($createCategoryUseCaseRequest);

            $payload = [
                'data' => $createCategoryUseCaseResponse,
            ];

            return $this->jsonResponse($response, $payload, 201);
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

            return $this->jsonResponse($response, $payload, $exception->getCode());
        } catch (Exception $exception) {
            $payload = [
                'error' => [
                    'code' => $exception->getCode(),
                    'message' => $exception->getMessage(),
                ],
            ];

            return $this->jsonResponse($response, $payload, $exception->getCode());
        }
    }

    public function show(Request $request, Response $response, string $id): Response
    {
        try {
            $showOneCategoryUseCaseRequest = new ShowOneCategoryUseCaseRequest($id);

            $showOneCategoryUseCaseResponse = $this->showOneCategoryUseCase->execute($showOneCategoryUseCaseRequest);

            $payload = [
                'data' => $showOneCategoryUseCaseResponse,
            ];

            return $this->jsonResponse($response, $payload);
        } catch (Exception $exception) {
            $payload = [
                'error' => [
                    'code' => $exception->getCode(),
                    'message' => $exception->getMessage(),
                ],
            ];

            return $this->jsonResponse($response, $payload, $exception->getCode());
        }
    }

    public function update(Request $request, Response $response, string $id): Response
    {
        try {
            ['name' => $name, 'icon' => $icon] = $request->getParsedBody();

            $validation = new ValidationResult();

            if (empty($name)) {
                $validation->addError('name', "O campo 'name' é obrigatório");
            }

            if (empty($icon)) {
                $validation->addError('icon', "O campo 'icon' é obrigatório");
            }

            if ($validation->fails()) {
                throw new ValidationException('Validation error', $validation);
            }

            $updateCategoryUseCaseRequest = new UpdateCategoryUseCaseRequest($id, $name, $icon);

            $updateCategoryUseCaseResponse = $this->updateCategoryUseCase->execute($updateCategoryUseCaseRequest);

            $payload = [
                'data' => $updateCategoryUseCaseResponse,
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

            return $this->jsonResponse($response, $payload, $exception->getCode());
        } catch (Exception $exception) {
            $payload = [
                'error' => [
                    'code' => $exception->getCode(),
                    'message' => $exception->getMessage(),
                ],
            ];

            return $this->jsonResponse($response, $payload, $exception->getCode());
        }
    }

    public function destroy(Request $request, Response $response, string $id): Response
    {
        try {
            $deleteCategoryUseCaseRequest = new DeleteCategoryUseCaseRequest($id);

            $this->deleteCategoryUseCase->execute($deleteCategoryUseCaseRequest);

            return $this->jsonResponse($response, [], 204);
        } catch (Exception $exception) {
            $payload = [
                'error' => [
                    'code' => $exception->getCode(),
                    'message' => $exception->getMessage(),
                ],
            ];

            return $this->jsonResponse($response, $payload, $exception->getCode());
        }
    }
}

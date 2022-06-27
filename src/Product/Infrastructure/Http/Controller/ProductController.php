<?php

declare(strict_types=1);

namespace Minascafe\Product\Infrastructure\Http\Controller;

use Exception;
use Minascafe\Product\Application\UseCase\CreateProductUseCase;
use Minascafe\Product\Application\UseCase\CreateProductUseCaseRequest;
use Minascafe\Product\Application\UseCase\DeleteProductUseCase;
use Minascafe\Product\Application\UseCase\DeleteProductUseCaseRequest;
use Minascafe\Product\Application\UseCase\ShowAllProductsUseCase;
use Minascafe\Product\Application\UseCase\ShowAllProductsUseCaseRequest;
use Minascafe\Product\Application\UseCase\ShowOneProductUseCase;
use Minascafe\Product\Application\UseCase\ShowOneProductUseCaseRequest;
use Minascafe\Product\Application\UseCase\UpdateProductUseCase;
use Minascafe\Product\Application\UseCase\UpdateProductUseCaseRequest;
use Minascafe\Shared\Infrastructure\Http\Controller\BaseController;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Selective\Validation\Exception\ValidationException;
use Selective\Validation\ValidationResult;

final class ProductController extends BaseController
{
    public function __construct(
        private readonly ShowAllProductsUseCase $showAllProductsUseCase,
        private readonly CreateProductUseCase $createProductUseCase,
        private readonly ShowOneProductUseCase $showOneProductUseCase,
        private readonly UpdateProductUseCase $updateProductUseCase,
        private readonly DeleteProductUseCase $deleteProductUseCase
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

            $showAllProductsUseCaseRequest = new ShowAllProductsUseCaseRequest($active, $order, $limit, $offset);

            $showAllProductsUseCaseResponse = $this->showAllProductsUseCase->execute($showAllProductsUseCaseRequest);

            $payload = [
                'data' => $showAllProductsUseCaseResponse,
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
            [
                'categoryId' => $categoryId,
                'name' => $name,
                'price' => $price,
                'active' => $active,
            ] = $request->getParsedBody();

            $validation = new ValidationResult();

            if (empty($categoryId)) {
                $validation->addError('categoryId', "O campo 'categoryId' é obrigatório");
            }

            if (empty($name)) {
                $validation->addError('name', "O campo 'name' é obrigatório");
            }

            if (empty($price)) {
                $validation->addError('price', "O campo 'price' é obrigatório");
            }

            if (null !== $active && '' === $active) {
                $validation->addError('active', "O campo 'active' é obrigatório");
            }

            if ($validation->fails()) {
                throw new ValidationException('Validation error', $validation);
            }

            $createProductUseCaseRequest = new CreateProductUseCaseRequest($categoryId, $name, $price, $active);

            $createProductUseCaseResponse = $this->createProductUseCase->execute($createProductUseCaseRequest);

            $payload = [
                'data' => $createProductUseCaseResponse,
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

    public function show(Request $request, Response $response, string $id): Response
    {
        try {
            $showOneProductUseCaseRequest = new ShowOneProductUseCaseRequest($id);

            $showOneProductUseCaseResponse = $this->showOneProductUseCase->execute($showOneProductUseCaseRequest);

            $payload = [
                'data' => $showOneProductUseCaseResponse,
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
            [
                'categoryId' => $categoryId,
                'name' => $name,
                'price' => $price,
                'active' => $active,
            ] = $request->getParsedBody();

            $validation = new ValidationResult();

            if (null !== $categoryId && '' === $categoryId) {
                $validation->addError('categoryId', "O campo 'categoryId' é obrigatório");
            }

            if (null !== $name && '' === $name) {
                $validation->addError('name', "O campo 'name' é obrigatório");
            }

            if (null !== $price && '' === $price) {
                $validation->addError('price', "O campo 'price' é obrigatório");
            }

            if (null !== $active && '' === $active) {
                $validation->addError('active', "O campo 'active' é obrigatório");
            }

            if ($validation->fails()) {
                throw new ValidationException('Validation error', $validation);
            }

            $updateProductUseCaseRequest = new UpdateProductUseCaseRequest($id, $categoryId, $name, $price, $active);

            $updateProductUseCaseResponse = $this->updateProductUseCase->execute($updateProductUseCaseRequest);

            $payload = [
                'data' => $updateProductUseCaseResponse,
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
            $deleteProductUseCaseRequest = new DeleteProductUseCaseRequest($id);

            $this->deleteProductUseCase->execute($deleteProductUseCaseRequest);

            $payload = [];

            return $this->jsonResponse($response, $payload, 204);
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

<?php

declare(strict_types=1);

namespace Minascafe\Product\Infrastructure\Http\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Minascafe\Category\Domain\Repository\CategoryRepositoryInterface;
use Minascafe\Category\Infrastructure\Persistence\Doctrine\Entity\Category as EntityCategory;
use Minascafe\Product\Application\UseCase\CreateProductUseCase;
use Minascafe\Product\Application\UseCase\CreateProductUseCaseRequest;
use Minascafe\Product\Application\UseCase\DeleteProductUseCase;
use Minascafe\Product\Application\UseCase\DeleteProductUseCaseRequest;
use Minascafe\Product\Application\UseCase\ShowAllProductsUseCase;
use Minascafe\Product\Application\UseCase\ShowOneProductUseCase;
use Minascafe\Product\Application\UseCase\ShowOneProductUseCaseRequest;
use Minascafe\Product\Application\UseCase\UpdateProductUseCase;
use Minascafe\Product\Application\UseCase\UpdateProductUseCaseRequest;
use Minascafe\Product\Domain\Repository\ProductRepositoryInterface;
use Minascafe\Product\Infrastructure\Persistence\Doctrine\Entity\Product as EntityProduct;
use Minascafe\Shared\Infrastructure\Http\Controller\BaseController;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

final class ProductController extends BaseController
{
    private CategoryRepositoryInterface $categoryRepository;

    private ProductRepositoryInterface $productRepository;

    public function __construct(ContainerInterface $container)
    {
        $entityManager = $container->get(EntityManagerInterface::class);
        $this->categoryRepository = $entityManager->getRepository(EntityCategory::class);
        $this->productRepository = $entityManager->getRepository(EntityProduct::class);
    }

    public function index(Request $request, Response $response): Response
    {
        try {
            $showAllProductsUseCase = new ShowAllProductsUseCase($this->productRepository);

            $showAllProductsUseCaseResponse = $showAllProductsUseCase->execute();

            $payload = [
                'data', $showAllProductsUseCaseResponse,
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
            ['categoryId' => $categoryId, 'name' => $name] = $request->getParsedBody();

            $createProductUseCase = new CreateProductUseCase($this->categoryRepository, $this->productRepository);

            $createProductUseCaseRequest = new CreateProductUseCaseRequest($categoryId, $name);

            $createProductUseCaseResponse = $createProductUseCase->execute($createProductUseCaseRequest);

            $payload = [
                'data' => $createProductUseCaseResponse,
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

    public function show(Request $request, Response $response, string $id): Response
    {
        try {
            $showOneProductUseCase = new ShowOneProductUseCase($this->productRepository);

            $showOneProductUseCaseRequest = new ShowOneProductUseCaseRequest($id);

            $showOneProductUseCaseResponse = $showOneProductUseCase->execute($showOneProductUseCaseRequest);

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
            ['categoryId' => $categoryId, 'name' => $name] = $request->getParsedBody();

            $updateProductUseCase = new UpdateProductUseCase($this->categoryRepository, $this->productRepository);

            $updateProductUseCaseRequest = new UpdateProductUseCaseRequest($id, $categoryId, $name);

            $updateProductUseCaseResponse = $updateProductUseCase->execute($updateProductUseCaseRequest);

            $payload = [
                'data' => $updateProductUseCaseResponse,
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

    public function destroy(Request $request, Response $response, string $id): Response
    {
        try {
            $deleteProductUseCase = new DeleteProductUseCase($this->productRepository);

            $deleteProductUseCaseRequest = new DeleteProductUseCaseRequest($id);

            $deleteProductUseCase->execute($deleteProductUseCaseRequest);

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

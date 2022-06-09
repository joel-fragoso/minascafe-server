<?php

declare(strict_types=1);

namespace Minascafe\Category\Infrastructure\Http\Controller;

use Doctrine\ORM\EntityManagerInterface;
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
use Minascafe\Category\Domain\Repository\CategoryRepositoryInterface;
use Minascafe\Category\Infrastructure\Persistence\Doctrine\Entity\Category;
use Minascafe\Shared\Infrastructure\Http\Controller\BaseController;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

final class CategoryController extends BaseController
{
    private CategoryRepositoryInterface $categoryRepository;

    public function __construct(ContainerInterface $container)
    {
        $entityManager = $container->get(EntityManagerInterface::class);
        $this->categoryRepository = $entityManager->getRepository(Category::class);
    }

    public function index(Request $request, Response $response): Response
    {
        try {
            $showAllCategoriesUseCase = new ShowAllCategoriesUseCase($this->categoryRepository);

            $payload = [
                'data' => $showAllCategoriesUseCase->execute(),
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
            ['name' => $name] = $request->getParsedBody();

            $createCategoryUseCase = new CreateCategoryUseCase($this->categoryRepository);

            $createCategoryUseCaseRequest = new CreateCategoryUseCaseRequest($name);

            $createCategoryUseCaseResponse = $createCategoryUseCase->execute($createCategoryUseCaseRequest);

            $payload = [
                'data' => $createCategoryUseCaseResponse,
            ];

            return $this->jsonResponse($response, $payload, 201);
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
            $showOneCategoryUseCase = new ShowOneCategoryUseCase($this->categoryRepository);

            $showOneCategoryUseCaseRequest = new ShowOneCategoryUseCaseRequest($id);

            $showOneCategoryUseCaseResponse = $showOneCategoryUseCase->execute($showOneCategoryUseCaseRequest);

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
            ['name' => $name] = $request->getParsedBody();

            $updateCategoryUseCase = new UpdateCategoryUseCase($this->categoryRepository);

            $updateCategoryUseCaseRequest = new UpdateCategoryUseCaseRequest($id, $name);

            $updateCategoryUseCaseResponse = $updateCategoryUseCase->execute($updateCategoryUseCaseRequest);

            $payload = [
                'data' => $updateCategoryUseCaseResponse,
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
            $deleteCategoryUseCase = new DeleteCategoryUseCase($this->categoryRepository);

            $deleteCategoryUseCaseRequest = new DeleteCategoryUseCaseRequest($id);

            $deleteCategoryUseCase->execute($deleteCategoryUseCaseRequest);

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

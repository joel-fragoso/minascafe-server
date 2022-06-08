<?php

declare(strict_types=1);

namespace Minascafe\Category\Infrastructure\Http\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Minascafe\Category\Application\UseCase\CreateCategoryUseCase;
use Minascafe\Category\Application\UseCase\CreateCategoryUseCaseRequest;
use Minascafe\Category\Application\UseCase\ShowAllCategoriesUseCase;
use Minascafe\Category\Application\UseCase\ShowOneCategoryUseCase;
use Minascafe\Category\Application\UseCase\ShowOneCategoryUseCaseRequest;
use Minascafe\Category\Application\UseCase\UpdateCategoryUseCase;
use Minascafe\Category\Application\UseCase\UpdateCategoryUseCaseRequest;
use Minascafe\Category\Infrastructure\Persistence\Doctrine\Entity\Category;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

final class CategoryController
{
    public function __construct(private ContainerInterface $container)
    {
    }

    public function index(Request $request, Response $response): Response
    {
        try {
            $entityManager = $this->container->get(EntityManagerInterface::class);
            $categoryRepository = $entityManager->getRepository(Category::class);
            $showAllCategoriesUseCase = new ShowAllCategoriesUseCase($categoryRepository);

            $payload = json_encode($showAllCategoriesUseCase->execute(), \JSON_PRETTY_PRINT);

            $response->getBody()->write($payload);

            return $response->withHeader('Content-type', 'application/json')->withStatus(200);
        } catch (Exception $exception) {
            $payload = json_encode($exception->getMessage(), \JSON_PRETTY_PRINT);

            $response->getBody()->write($payload);

            return $response->withHeader('Content-type', 'application/json')->withStatus($exception->getCode());
        }
    }

    public function create(Request $request, Response $response): Response
    {
        try {
            ['name' => $name] = $request->getParsedBody();

            $entityManager = $this->container->get(EntityManagerInterface::class);
            $categoryRepository = $entityManager->getRepository(Category::class);
            $createCategoryUseCase = new CreateCategoryUseCase($categoryRepository);

            $createCategoryUseCaseRequest = new CreateCategoryUseCaseRequest($name);

            $createCategoryUseCaseResponse = $createCategoryUseCase->execute($createCategoryUseCaseRequest);

            $payload = json_encode($createCategoryUseCaseResponse, \JSON_PRETTY_PRINT);

            $response->getBody()->write($payload);

            return $response->withHeader('Content-type', 'application/json')->withStatus(201);
        } catch (Exception $exception) {
            $payload = json_encode($exception->getMessage(), \JSON_PRETTY_PRINT);

            $response->getBody()->write($payload);

            return $response->withHeader('Content-type', 'application/json')->withStatus($exception->getCode());
        }
    }

    public function show(Request $request, Response $response, string $id): Response
    {
        try {
            $entityManager = $this->container->get(EntityManagerInterface::class);
            $categoryRepository = $entityManager->getRepository(Category::class);
            $showOneCategoryUseCase = new ShowOneCategoryUseCase($categoryRepository);

            $showOneCategoryUseCaseRequest = new ShowOneCategoryUseCaseRequest($id);

            $showOneCategoryUseCaseResponse = $showOneCategoryUseCase->execute($showOneCategoryUseCaseRequest);

            $payload = json_encode($showOneCategoryUseCaseResponse, \JSON_PRETTY_PRINT);

            $response->getBody()->write($payload);

            return $response->withHeader('Content-type', 'application/json')->withStatus(200);
        } catch (Exception $exception) {
            $payload = json_encode($exception->getMessage(), \JSON_PRETTY_PRINT);

            $response->getBody()->write($payload);

            return $response->withHeader('Content-type', 'application/json')->withStatus($exception->getCode());
        }
    }

    public function update(Request $request, Response $response, string $id): Response
    {
        try {
            ['name' => $name] = $request->getParsedBody();

            $entityManager = $this->container->get(EntityManagerInterface::class);
            $categoryRepository = $entityManager->getRepository(Category::class);
            $updateCategoryUseCase = new UpdateCategoryUseCase($categoryRepository);

            $updateCategoryUseCaseRequest = new UpdateCategoryUseCaseRequest($id, $name);

            $updateCategoryUseCaseResponse = $updateCategoryUseCase->execute($updateCategoryUseCaseRequest);

            $payload = json_encode($updateCategoryUseCaseResponse, \JSON_PRETTY_PRINT);

            $response->getBody()->write($payload);

            return $response->withHeader('Content-type', 'application/json')->withStatus(200);
        } catch (Exception $exception) {
            $payload = json_encode($exception->getMessage(), \JSON_PRETTY_PRINT);

            $response->getBody()->write($payload);

            return $response->withHeader('Content-type', 'application/json')->withStatus(400);
        }
    }
}

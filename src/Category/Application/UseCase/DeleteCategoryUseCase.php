<?php

declare(strict_types=1);

namespace Minascafe\Category\Application\UseCase;

use Minascafe\Category\Domain\Exception\CategoryNotFoundException;
use Minascafe\Category\Domain\Repository\CategoryRepositoryInterface;
use Minascafe\Category\Domain\ValueObject\CategoryId;
use Minascafe\Shared\Application\Adapter\CacheAdapterInterface;

final class DeleteCategoryUseCase
{
    public function __construct(
        private CategoryRepositoryInterface $categoryRepository,
        private CacheAdapterInterface $cacheAdapter,
    ) {
    }

    /**
     * @throws CategoryNotFoundException
     */
    public function execute(DeleteCategoryUseCaseRequest $deleteCategoryUseCaseRequest): void
    {
        $categoryId = $deleteCategoryUseCaseRequest->categoryId();

        $findCategory = $this->categoryRepository->findById(new CategoryId($categoryId));

        if (!$findCategory) {
            throw new CategoryNotFoundException("A categoria '{$categoryId}' não foi encontrada");
        }

        $this->categoryRepository->delete($findCategory);

        $this->cacheAdapter->delete('show-all-categories');
    }
}

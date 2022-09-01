<?php

declare(strict_types=1);

namespace Minascafe\Category\Application\UseCase;

use Minascafe\Category\Domain\Repository\CategoryRepositoryInterface;
use Minascafe\Shared\Application\Adapter\CacheAdapterInterface;

final class ShowAllCategoriesUseCase
{
    public function __construct(
        private CategoryRepositoryInterface $categoryRepository,
        private CacheAdapterInterface $cacheAdapter,
    ) {
    }

    public function execute(
        ShowAllCategoriesUseCaseRequest $showAllCategoriesUseCaseRequest
    ): ShowAllCategoriesUseCaseResponse {
        if (
            null === $showAllCategoriesUseCaseRequest->active()
            || null === $showAllCategoriesUseCaseRequest->order()
            || null === $showAllCategoriesUseCaseRequest->limit()
            || null === $showAllCategoriesUseCaseRequest->offset()
        ) {
            $this->cacheAdapter->delete('show-all-categories');
        }

        $categories = $this->cacheAdapter->recover('show-all-categories');

        if (
            null !== $showAllCategoriesUseCaseRequest->active()
            || null !== $showAllCategoriesUseCaseRequest->order()
            || null !== $showAllCategoriesUseCaseRequest->limit()
            || null !== $showAllCategoriesUseCaseRequest->offset()
            || !$categories
        ) {
            $categories = $this->categoryRepository->findAll(
                $showAllCategoriesUseCaseRequest->active(),
                $showAllCategoriesUseCaseRequest->order(),
                $showAllCategoriesUseCaseRequest->limit(),
                $showAllCategoriesUseCaseRequest->offset(),
            );

            $this->cacheAdapter->save('show-all-categories', $categories);
        }

        return new ShowAllCategoriesUseCaseResponse($categories);
    }
}

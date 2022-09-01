<?php

declare(strict_types=1);

namespace Minascafe\Product\Application\UseCase;

use Minascafe\Product\Domain\Repository\ProductRepositoryInterface;
use Minascafe\Shared\Application\Adapter\CacheAdapterInterface;

final class ShowAllProductsUseCase
{
    public function __construct(
        private ProductRepositoryInterface $productRepository,
        private CacheAdapterInterface $cacheAdapter,
    ) {
    }

    public function execute(
        ShowAllProductsUseCaseRequest $showAllProductsUseCaseRequest
    ): ShowAllProductsUseCaseResponse {
        if (
            null === $showAllProductsUseCaseRequest->active()
            || null === $showAllProductsUseCaseRequest->order()
            || null === $showAllProductsUseCaseRequest->limit()
            || null === $showAllProductsUseCaseRequest->offset()
        ) {
            $this->cacheAdapter->delete('show-all-products');
        }

        $products = $this->cacheAdapter->recover('show-all-products');

        if (
            null !== $showAllProductsUseCaseRequest->active()
            || null !== $showAllProductsUseCaseRequest->order()
            || null !== $showAllProductsUseCaseRequest->limit()
            || null !== $showAllProductsUseCaseRequest->offset()
            || !$products
        ) {
            $products = $this->productRepository->findAll(
                $showAllProductsUseCaseRequest->active(),
                $showAllProductsUseCaseRequest->order(),
                $showAllProductsUseCaseRequest->limit(),
                $showAllProductsUseCaseRequest->offset(),
            );

            $this->cacheAdapter->save('show-all-products', $products);
        }

        return new ShowAllProductsUseCaseResponse($products);
    }
}

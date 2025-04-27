<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\View;

use Raketa\BackendTestTask\Entity\Product;
use Raketa\BackendTestTask\Repository\ProductRepository;
use Raketa\BackendTestTask\View\Interface\ViewInterface;

readonly class ProductsView implements ViewInterface
{
    public function __construct(
        private ProductRepository $productRepository
    ) {
    }

    public function toArray(mixed $toArrayData): array
    {
        $products = $this->productRepository->getByCategory($toArrayData);

        if (empty($products)) {
            return [];
        }

        return array_map(
            function (Product $product) {
                return [
                    'uuid' => $product->getUuid(),
                    'name' => $product->getName(),
                    'category' => $product->getCategory(),
                    'description' => $product->getDescription(),
                    'thumbnail' => $product->getThumbnail(),
                    'price' => $product->getPrice(),
                    'is_active' => $product->isActive(),
                ];
            },
            $products
        );
    }
}

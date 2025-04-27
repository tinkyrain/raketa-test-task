<?php

declare(strict_types = 1);

namespace Raketa\BackendTestTask\View;

use InvalidArgumentException;
use Raketa\BackendTestTask\Domain\Cart\Cart;
use Raketa\BackendTestTask\Repository\ProductRepository;
use Raketa\BackendTestTask\View\Interface\ViewInterface;

readonly class CartView implements ViewInterface
{
    public function __construct(
        private ProductRepository $productRepository
    ) {
    }

    public function toArray(mixed $toArrayData): array
    {
        if (!$toArrayData instanceof Cart) {
            throw new InvalidArgumentException('Input must be an instance of Cart');
        }

        $cart = $toArrayData;
        $cartItems = $cart->getItems();
        
        $productUuids = array_map(fn($item) => $item->getProductUuid(), $cartItems);
        
        $products = $this->productRepository->getByUuids($productUuids);
        
        if (empty($products)) {
            throw new InvalidArgumentException('No products found for the cart items');
        }

        $productsMap = array_reduce($products, function ($acc, $product) {
            $acc[$product->getUuid()] = $product;
            return $acc;
        }, []);

        $data = [
            'uuid' => $cart->getUuid(),
            'customer' => [
                'name' => implode(' ', [
                    $cart->getCustomer()->getLastName(),
                    $cart->getCustomer()->getFirstName(),
                    $cart->getCustomer()->getMiddleName(),
                ]),
                'email' => $cart->getCustomer()->getEmail(),
            ],
            'items' => [],
            'total' => 0,
        ];

        foreach ($cartItems as $item) {
            $product = $productsMap[$item->getProductUuid()] ?? null;
            
            if (!$product) {
                continue; 
            }

            $itemTotal = $item->getPrice() * $item->getQuantity();
            $data['total'] += $itemTotal;

            $data['items'][] = [
                'uuid' => $item->getUuid(),
                'price' => $item->getPrice(),
                'quantity' => $item->getQuantity(),
                'total' => $itemTotal,
                'product' => [
                    'uuid' => $product->getUuid(),
                    'name' => $product->getName(),
                    'thumbnail' => $product->getThumbnail(),
                    'price' => $product->getPrice(),
                    'is_active' => $product->isActive(),
                ],
            ];
        }

        return $data;
    }
}

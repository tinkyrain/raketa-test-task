<?php

declare(strict_types = 1);

namespace Raketa\BackendTestTask\Domain\Cart;

use Ramsey\Uuid\Uuid;

final readonly class CartItem
{
    private string $uuid;
    private string $productUuid;
    private float $price;
    private int $quantity;

    public function __construct(
        string $productUuid,
        float $price,
        int $quantity,
    ) {
        $this->uuid = Uuid::uuid4()->toString();
        $this->productUuid = $productUuid;
        $this->price = $price;
        $this->quantity = $quantity;
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function getProductUuid(): string
    {
        return $this->productUuid;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }
}

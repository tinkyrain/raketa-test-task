<?php

declare(strict_types = 1);

namespace Raketa\BackendTestTask\Domain\Cart;

use Raketa\BackendTestTask\Entity\Customer;
use Raketa\BackendTestTask\Domain\Cart\CartItem;

final class Cart
{
    public function __construct(
        readonly private string $uuid,
        readonly private Customer $customer,
        private array $items,
    ) {
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function getCustomer(): Customer
    {
        return $this->customer;
    }

    public function getItems(): array
    {
        return $this->items;
    }

    public function addItem(CartItem $item): void
    {
        $this->items[] = $item;
    }
}

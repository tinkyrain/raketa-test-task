<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\Service;

use Exception;
use Psr\Log\LoggerInterface;
use Raketa\BackendTestTask\Domain\Cart\Cart;
use Raketa\BackendTestTask\Repository\CustomerRepository;
use Raketa\BackendTestTask\Infrastructure\Interface\ConnectorInterface;
use RuntimeException;

class CartService
{
    private const CART_KEY_PREFIX = 'cart:';
    
    public function __construct(
        private readonly ConnectorInterface $connector,
        private readonly CustomerRepository $customerRepository,
        private readonly ?LoggerInterface $logger = null,
    ) {
    }

    public function saveCart(Cart $cart, string $sessionId): bool
    {
        $key = self::CART_KEY_PREFIX . $sessionId;

        try {
            $this->connector->set($key, $cart);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function getCart(string $sessionId): Cart
    {
        try {
            $key = self::CART_KEY_PREFIX . $sessionId;
            $cart = $this->connector->get($key);
            
            if ($cart instanceof Cart) {
                return $cart;
            }
            
            $customer = $this->customerRepository->getCustomerBySessionId($sessionId);
            return new Cart(self::CART_KEY_PREFIX . $sessionId, $customer, []);
        } catch (Exception $e) {
            throw new RuntimeException('Failed to retrieve cart: ' . $e->getMessage(), 0, $e);
        }
    }
}

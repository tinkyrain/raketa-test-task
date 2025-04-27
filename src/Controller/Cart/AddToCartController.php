<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\Controller\Cart;

use Raketa\BackendTestTask\Controller\Controller;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Raketa\BackendTestTask\Domain\Cart\CartItem;
use Raketa\BackendTestTask\Service\CartService;
use Raketa\BackendTestTask\Repository\ProductRepository;
use Raketa\BackendTestTask\View\CartView;
use Exception;

readonly class AddToCartController extends Controller
{
    public function __construct(
        private ProductRepository $productRepository,
        private CartView $cartView,
        private CartService $cartService,
    ) {}

    public function add(RequestInterface $request): ResponseInterface
    {
        try {
            $rawRequest = json_decode($request->getBody()->getContents(), true);
            $product = $this->productRepository->getByUuid($rawRequest['productUuid']);

            if (!$product) return $this->errorResponse('Product not found');

            $cart = $this->cartService->getCart(session_id());
            $cart->addItem(new CartItem(
                $product->getUuid(),
                $product->getPrice(),
                $rawRequest['quantity'] ?? 1,
            ));

            return $this->successResponse([
                'status' => 'success',
                'cart' => $this->cartView->toArray($cart)
            ]);
        } catch (Exception $e) {
            return $this->errorResponse('Add product to cart error', ['Content-Type' => 'application/json; charset=utf-8'], $e->getCode());
        }
    }
}

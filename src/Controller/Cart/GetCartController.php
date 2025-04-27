<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\Controller;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Raketa\BackendTestTask\Service\CartService;
use Raketa\BackendTestTask\View\CartView;
use Raketa\BackendTestTask\Controller\Controller;

readonly class GetCartController extends Controller
{
    public function __construct(
        private CartView $cartView,
        private CartService $cartService
    ) {}

    public function get(RequestInterface $request): ResponseInterface
    {
        $cart = $this->cartService->getCart(session_id());

        if (!$cart)
            return $this->errorResponse('Cart not found', ['Content-Type' => 'application/json; charset=utf-8'], 404);

        return $this->successResponse($this->cartView->toArray($cart));
    }
}

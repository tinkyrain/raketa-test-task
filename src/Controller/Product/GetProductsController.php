<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\Controller\Product;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Raketa\BackendTestTask\View\ProductsView;
use Raketa\BackendTestTask\Controller\Controller;
use Exception;

readonly class GetProductsController extends Controller
{
    public function __construct(
        private ProductsView $productsView
    ) {}

    public function get(RequestInterface $request): ResponseInterface
    {
        try {
            $rawRequest = json_decode($request->getBody()->getContents(), true);
            return $this->successResponse($this->productsView->toArray($rawRequest['category']));
        } catch (Exception $e) {
            return $this->errorResponse('Get products error', ['Content-Type' => 'application/json; charset=utf-8'], $e->getCode());
        }
    }
}

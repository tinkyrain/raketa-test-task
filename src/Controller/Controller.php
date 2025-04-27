<?php

namespace Raketa\BackendTestTask\Controller;

use Raketa\BackendTestTask\Response\JsonResponse;

abstract readonly class Controller
{
    /**
     * @var JsonResponse
     */
    protected JsonResponse $response;

    public function __construct()
    {
        $this->response = new JsonResponse();
    }

    /**
     * Success response
     * 
     * @param mixed $data
     * @param int $status
     * @return JsonResponse
     */
    protected function successResponse(mixed $data, array $headers = ['Content-Type' => 'application/json; charset=utf-8'], int $status = 200): JsonResponse
    {
        $this->response->getBody()->write(json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

        foreach ($headers as $key => $value) {
            $this->response->withHeader($key, $value);
        }

        return $this->response
            ->withStatus($status);
    }

    /**
     * Error response
     * 
     * @param string $message
     * @param int $status
     * @return JsonResponse
     */
    protected function errorResponse(string $message, array $headers = ['Content-Type' => 'application/json; charset=utf-8'], int $status = 400): JsonResponse
    {
        $this->response->getBody()->write(json_encode(['error' => $message], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

        foreach ($headers as $key => $value) {
            $this->response->withHeader($key, $value);
        }

        return $this->response
            ->withStatus($status);
    }
}

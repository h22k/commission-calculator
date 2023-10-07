<?php

namespace H22k\CommissionCalculator;

final readonly class RequestOption
{
    public function __construct(
        private string $method,
        private string $uri,
        private array $data = [],
        private array $headers = []
    ) {
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @return string
     */
    public function getUri(): string
    {
        return $this->uri;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @return array
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }
}
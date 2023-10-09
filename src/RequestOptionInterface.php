<?php

namespace H22k\CommissionCalculator;

interface RequestOptionInterface
{
    /**
     * @return string
     */
    public function getMethod(): string;

    /**
     * @return string
     */
    public function getUri(): string;

    /**
     * @return array
     */
    public function getData(): array;

    /**
     * @return array
     */
    public function getHeaders(): array;
}
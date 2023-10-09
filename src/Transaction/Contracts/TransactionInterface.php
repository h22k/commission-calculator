<?php

namespace H22k\CommissionCalculator\Transaction\Contracts;

interface TransactionInterface
{
    /**
     * @return string
     */
    public function getBin(): string;

    /**
     * @return float
     */
    public function getAmount(): float;

    /**
     * @return string
     */
    public function getCurrency(): string;
}
<?php

namespace H22k\CommissionCalculator\Transaction;

final readonly class Transaction
{
    public function __construct(
        private string $bin,
        private float $amount,
        private string $currency
    ) {
    }

    /**
     * @return string
     */
    public function getBin(): string
    {
        return $this->bin;
    }

    /**
     * @return float
     */
    public function getAmount(): float
    {
        return $this->amount;
    }

    /**
     * @return string
     */
    public function getCurrency(): string
    {
        return $this->currency;
    }
}
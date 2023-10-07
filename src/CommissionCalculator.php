<?php

namespace H22k\CommissionCalculator;

use H22k\CommissionCalculator\Bin\Contracts\BinClient;
use H22k\CommissionCalculator\Rate\Contracts\RateClient;
use H22k\CommissionCalculator\Transaction\Transaction;

final readonly class CommissionCalculator
{
    public function __construct(
        private BinClient $binClient,
        private RateClient $rateClient,
        private float $europeanCommissionRate,
        private float $notEuropeanCommissionRate
    ) {
    }

    public function calculate(Transaction $transaction): float
    {
        $baseValue = $this->rateClient->getBaseValue($transaction);
        $commission = $this->getCommission($transaction);

        return round($baseValue * $commission, 2);
    }

    private function getCommission(Transaction $transaction): float
    {
        $isEuropean = $this->binClient->isEuropean($transaction);

        return $isEuropean
            ? $this->europeanCommissionRate
            : $this->notEuropeanCommissionRate;
    }
}
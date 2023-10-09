<?php

namespace H22k\CommissionCalculator;

use Exception;
use H22k\CommissionCalculator\Bin\BaseBinClient;
use H22k\CommissionCalculator\Exception\Bin\BinHTTPException;
use H22k\CommissionCalculator\Rate\BaseRateClient;
use H22k\CommissionCalculator\Transaction\Contracts\TransactionInterface;

final readonly class CommissionCalculator
{
    public function __construct(
        private BaseBinClient $binClient,
        private BaseRateClient $rateClient,
        private float $europeanCommissionRate,
        private float $notEuropeanCommissionRate
    ) {
    }

    /**
     * @throws Exception
     */
    public function calculate(TransactionInterface $transaction): float
    {
        $baseValue = $this->rateClient->getBaseValue($transaction);
        $commissionRate = $this->getCommissionRate($transaction);

        return round($baseValue * $commissionRate, 2);
    }

    /**
     * @throws BinHTTPException
     */
    private function getCommissionRate(TransactionInterface $transaction): float
    {
        $isEuropean = $this->binClient->isEuropean($transaction);

        return $isEuropean
            ? $this->europeanCommissionRate
            : $this->notEuropeanCommissionRate;
    }
}
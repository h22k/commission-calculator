<?php

namespace H22k\CommissionCalculator\Rate\Contracts;

use H22k\CommissionCalculator\Transaction\Contracts\TransactionInterface;

interface RateClient
{
    public function getBaseValue(TransactionInterface $transaction): float;
}
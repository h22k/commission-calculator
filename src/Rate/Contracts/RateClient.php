<?php

namespace H22k\CommissionCalculator\Rate\Contracts;

use H22k\CommissionCalculator\Transaction\Transaction;

interface RateClient
{
    public function getBaseValue(Transaction $transaction): float;
}
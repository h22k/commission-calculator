<?php

namespace H22k\CommissionCalculator\Bin\Contracts;

use H22k\CommissionCalculator\Transaction\Contracts\TransactionInterface;

interface BinClient
{
    public function isEuropean(TransactionInterface $transaction): bool;
}
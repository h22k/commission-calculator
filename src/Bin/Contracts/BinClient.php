<?php

namespace H22k\CommissionCalculator\Bin\Contracts;

use H22k\CommissionCalculator\Transaction\Transaction;

interface BinClient
{
    public function isEuropean(Transaction $transaction): bool;
}
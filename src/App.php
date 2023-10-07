<?php

namespace H22k\CommissionCalculator;

use H22k\CommissionCalculator\Reader\Reader;

final readonly class App
{
    public function __construct(
        private Reader $reader,
        private CommissionCalculator $commissionCalculator
    ) {
    }

    public function run(): void
    {
        $transactions = $this->reader->getTransactions();

        foreach ($transactions as $transaction) {
            echo $this->commissionCalculator->calculate($transaction) . "\n";
        }
    }
}
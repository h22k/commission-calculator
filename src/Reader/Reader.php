<?php

namespace H22k\CommissionCalculator\Reader;

use H22k\CommissionCalculator\Reader\Contracts\ReadStrategy;
use H22k\CommissionCalculator\Transaction\Transaction;

final readonly class Reader
{
    public function __construct(private ReadStrategy $readStrategy)
    {
    }

    /**
     * @return Transaction[]
     */
    public function getTransactions(): array
    {
        return $this->readStrategy->read();
    }
}
<?php

namespace H22k\CommissionCalculator\Reader\Contracts;

use H22k\CommissionCalculator\Transaction\Transaction;

interface ReadStrategy
{
    /**
     * @return Transaction[]
     */
    public function read(): array;
}
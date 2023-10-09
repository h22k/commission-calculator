<?php

namespace H22k\CommissionCalculator\Reader\Contracts;

use H22k\CommissionCalculator\Reader\File;
use H22k\CommissionCalculator\Transaction\Transaction;

interface ReadStrategy
{
    public function __construct(File $file);

    /**
     * @return Transaction[]
     */
    public function read(): array;
}
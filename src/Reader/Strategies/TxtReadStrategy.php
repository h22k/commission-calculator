<?php

namespace H22k\CommissionCalculator\Reader\Strategies;

use H22k\CommissionCalculator\Exception\Reader\BadExtensionException;
use H22k\CommissionCalculator\Exception\Reader\BadTransactionException;
use H22k\CommissionCalculator\Exception\Reader\FileNotFoundException;
use H22k\CommissionCalculator\Reader\Contracts\ReadStrategy;
use H22k\CommissionCalculator\Transaction\Transaction;

class TxtReadStrategy implements ReadStrategy
{
    /**
     * @var resource
     */
    private $file;

    /**
     * @throws BadExtensionException|FileNotFoundException
     */
    public function __construct(string $fileName)
    {
        if (!str_ends_with($fileName, '.txt')) {
            throw new BadExtensionException(sprintf('%s is not a .txt file!', $fileName));
        }

        if (!file_exists($fileName)) {
            throw new FileNotFoundException($fileName);
        }

        $this->file = fopen($fileName, 'r');
    }

    public function __destruct()
    {
        fclose($this->file);
    }

    /**
     * @return Transaction[]
     * @throws BadTransactionException
     */
    public function read(): array
    {
        $transactions = [];
        while ($buffer = fgets($this->file)) {
            $transactions[] = $this->createTransactionBy($buffer);
        }

        return $transactions;
    }

    /**
     * @throws BadTransactionException
     */
    private function createTransactionBy(string $buffer): Transaction
    {
        $transactionAsJson = json_decode($buffer);

        if (!$this->isTransactionAcceptable($transactionAsJson)) {
            throw new BadTransactionException(
                'There is something wrong with this transaction: %s', json_encode($transactionAsJson ?? [])
            );
        }

        return new Transaction(
            bin: $transactionAsJson->bin,
            amount: $transactionAsJson->amount,
            currency: $transactionAsJson->currency
        );
    }

    private function isTransactionAcceptable(object $transactionAsJson): bool
    {
        return $transactionAsJson
            && ($transactionAsJson->amount && is_float($transactionAsJson->amount))
            && ($transactionAsJson->currency && is_string($transactionAsJson->currency))
            && ($transactionAsJson->bin && is_string($transactionAsJson->bin));
    }
}
<?php

namespace H22k\CommissionCalculator\Reader\Strategies;

use H22k\CommissionCalculator\Exception\Reader\BadExtensionException;
use H22k\CommissionCalculator\Exception\Reader\BadTransactionException;
use H22k\CommissionCalculator\Reader\Contracts\ReadStrategy;
use H22k\CommissionCalculator\Reader\File;
use H22k\CommissionCalculator\Transaction\Transaction;

readonly class TxtReadStrategy implements ReadStrategy
{
    /**
     * @throws BadExtensionException
     */
    public function __construct(private File $file)
    {
        if (!str_ends_with($this->file->getFileName(), '.txt')) {
            throw new BadExtensionException(sprintf('%s is not a .txt file!', $this->file->getFileName()));
        }
    }

    /**
     * @return Transaction[]
     * @throws BadTransactionException
     */
    public function read(): array
    {
        $transactions = [];

        foreach ($this->file->readByLine() as $buffer) {
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
                sprintf('There is something wrong with this transaction: %s', json_encode($transactionAsJson ?? []))
            );
        }

        return new Transaction(
            bin: $transactionAsJson->bin,
            amount: (float)$transactionAsJson->amount,
            currency: $transactionAsJson->currency
        );
    }

    private function isTransactionAcceptable(object $transactionAsJson): bool
    {
        return $transactionAsJson
            && ($transactionAsJson->amount && is_numeric($transactionAsJson->amount))
            && ($transactionAsJson->currency && is_string($transactionAsJson->currency))
            && ($transactionAsJson->bin && is_string($transactionAsJson->bin));
    }
}
<?php

namespace H22k\CommissionCalculator;

use GuzzleHttp\ClientInterface;
use H22k\CommissionCalculator\Transaction\Contracts\TransactionInterface;
use Psr\Http\Message\ResponseInterface;

abstract class BaseClient
{
    public function __construct(
        protected ClientInterface $client,
        protected RequestOptionInterface $requestOption
    ) {
    }

    abstract protected function sendRequest(TransactionInterface $transaction): ResponseInterface;
}
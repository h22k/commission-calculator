<?php

namespace H22k\CommissionCalculator;

use GuzzleHttp\Client;
use H22k\CommissionCalculator\Transaction\Transaction;
use Psr\Http\Message\ResponseInterface;

abstract class BaseClient
{
    public function __construct(
        protected Client $client,
        protected RequestOption $requestOption
    ) {
    }

    abstract protected function sendRequest(Transaction $transaction): ResponseInterface;
}
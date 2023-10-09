<?php

namespace H22k\CommissionCalculator\Rate;

use GuzzleHttp\Client;
use H22k\CommissionCalculator\BaseClient;
use H22k\CommissionCalculator\Rate\Contracts\RateClient;
use H22k\CommissionCalculator\RequestOptionInterface;
use H22k\CommissionCalculator\Transaction\Contracts\TransactionInterface;
use Psr\Http\Message\ResponseInterface;

abstract class BaseRateClient extends BaseClient implements RateClient
{
    public function __construct(
        Client $client,
        RequestOptionInterface $requestOption,
        protected readonly string $baseCurrency
    ) {
        parent::__construct($client, $requestOption);
    }

    abstract protected function getRate(ResponseInterface $response, string $currency): float;

    public function getBaseValue(TransactionInterface $transaction): float
    {
        if ($this->isBaseCurrency($transaction->getCurrency())) {
            return $transaction->getAmount();
        }

        $response = $this->sendRequest($transaction);
        $rate = $this->getRate($response, $transaction->getCurrency());

        return round($transaction->getAmount() / $rate, 2);
    }

    private function isBaseCurrency(string $currency): bool
    {
        return $currency === $this->baseCurrency;
    }
}
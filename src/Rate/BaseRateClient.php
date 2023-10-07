<?php

namespace H22k\CommissionCalculator\Rate;

use Exception;
use GuzzleHttp\Client;
use H22k\CommissionCalculator\BaseClient;
use H22k\CommissionCalculator\Rate\Contracts\RateClient;
use H22k\CommissionCalculator\RequestOption;
use H22k\CommissionCalculator\Transaction\Transaction;
use Psr\Http\Message\ResponseInterface;

abstract class BaseRateClient extends BaseClient implements RateClient
{

    public function __construct(
        Client $client,
        RequestOption $requestOption,
        protected readonly string $baseCurrency
    ) {
        parent::__construct($client, $requestOption);
    }

    abstract protected function getRate(ResponseInterface $response, string $currency): float;

    public function getBaseValue(Transaction $transaction): float
    {
        if ($this->isBaseCurrency($transaction->getCurrency())) {
            return $transaction->getAmount();
        }

        try {
            $response = $this->sendRequest($transaction);
            $rate = $this->getRate($response, $transaction->getCurrency());
        } catch (Exception $exception) {
            throw $exception;
        }

        return $transaction->getAmount() / $rate;
    }

    private function isBaseCurrency(string $currency): bool
    {
        return $currency === $this->baseCurrency;
    }
}
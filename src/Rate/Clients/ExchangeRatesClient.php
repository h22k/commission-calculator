<?php

namespace H22k\CommissionCalculator\Rate\Clients;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use H22k\CommissionCalculator\Rate\BaseRateClient;
use H22k\CommissionCalculator\RequestOption;
use H22k\CommissionCalculator\Transaction\Transaction;
use Psr\Http\Message\ResponseInterface;

class ExchangeRatesClient extends BaseRateClient
{

    public function __construct(
        Client $client,
        RequestOption $requestOption,
        string $baseCurrency,
        private readonly string $apiAccessKey
    ) {
        parent::__construct($client, $requestOption, $baseCurrency);
    }

    /**
     * @throws GuzzleException
     */
    protected function sendRequest(Transaction $transaction): ResponseInterface
    {
        return $this->client->request(
            method: $this->requestOption->getMethod(),
            uri: $this->requestOption->getUri(),
            options: [
                'query' => [
                    'base' => $this->baseCurrency,
                    'symbols' => $transaction->getCurrency(),
                    'access_key' => $this->apiAccessKey
                ]
            ]
        );
    }

    protected function getRate(ResponseInterface $response): float
    {
        $body = json_decode($response->getBody()->getContents());

        return reset($body->rates);
    }
}
<?php

namespace H22k\CommissionCalculator\Bin\Clients;

use GuzzleHttp\Exception\GuzzleException;
use H22k\CommissionCalculator\Bin\BaseBinClient;
use H22k\CommissionCalculator\Transaction\Transaction;
use Psr\Http\Message\ResponseInterface;

class BinListClient extends BaseBinClient
{

    protected function getCountryCode(ResponseInterface $response): string
    {
        $body = json_decode($response->getBody()->getContents());

        return $body->country->alpha2;
    }

    /**
     * @throws GuzzleException
     */
    protected function sendRequest(Transaction $transaction): ResponseInterface
    {
        return $this->client->request(
            method: $this->requestOption->getMethod(),
            uri: sprintf('%s/%s', $this->requestOption->getUri(), $transaction->getBin())
        );
    }
}
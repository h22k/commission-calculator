<?php

namespace H22k\CommissionCalculator\Bin;

use H22k\CommissionCalculator\BaseClient;
use H22k\CommissionCalculator\Bin\Contracts\BinClient;
use H22k\CommissionCalculator\Bin\Enums\EuropeanCountryEnum;
use H22k\CommissionCalculator\Exception\Bin\BinHTTPException;
use H22k\CommissionCalculator\Transaction\Contracts\TransactionInterface;
use Psr\Http\Message\ResponseInterface;

abstract class BaseBinClient extends BaseClient implements BinClient
{
    abstract protected function getCountryCode(ResponseInterface $response): string;

    /**
     * @throws BinHTTPException
     */
    public function isEuropean(TransactionInterface $transaction): bool
    {
        try {
            $response = $this->sendRequest($transaction);
            $country = $this->getCountryCode($response);
        } catch (\Exception $exception) {
            throw new BinHTTPException($exception->getMessage());
        }

        return EuropeanCountryEnum::isEuropeanCountry($country);
    }
}
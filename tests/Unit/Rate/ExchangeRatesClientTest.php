<?php

namespace APP\Tests\Unit\Rate;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use H22k\CommissionCalculator\Rate\Clients\ExchangeRatesClient;
use H22k\CommissionCalculator\RequestOptionInterface;
use H22k\CommissionCalculator\Transaction\Contracts\TransactionInterface;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;

class ExchangeRatesClientTest extends TestCase
{
    private MockObject $clientMock;
    private MockObject $requestOptionMock;
    private MockObject $transactionMock;

    protected function setUp(): void
    {
        $this->clientMock = $this->createMock(Client::class);
        $this->requestOptionMock = $this->createMock(RequestOptionInterface::class);
        $this->transactionMock = $this->createMock(TransactionInterface::class);
    }

    public static function transactionProvider(): array
    {
        return [
            [
                'response' => new Response(200, body: '{"rates": {"TRY": 10.15}}'),
                'amount' => 425,
                'currency' => 'TRY',
                'base_value' => 41.87
            ]
        ];
    }

    #[DataProvider('transactionProvider')]
    public function test_getbasevalue_method_should_calculate_as_expected(
        ResponseInterface $response,
        float $amount,
        string $currency,
        float $baseValue
    ): void {
        $this->clientMock
            ->expects($this->once())
            ->method('request')
            ->willReturn($response);

        $this->transactionMock
            ->expects($this->atLeast(1))
            ->method('getCurrency')
            ->willReturn($currency);

        $this->transactionMock
            ->expects($this->atLeast(1))
            ->method('getAmount')
            ->willReturn($amount);

        $exchangeRatesClient = new ExchangeRatesClient(
            client: $this->clientMock,
            requestOption: $this->requestOptionMock,
            baseCurrency: 'NOT_TRY',
            apiAccessKey: 'API_KEY'
        );

        $actualBaseValue = $exchangeRatesClient->getBaseValue($this->transactionMock);

        $this->assertEquals($baseValue, $actualBaseValue);
    }

    public function test_getbasevalue_method_should_return_transaction_amount_if_transactions_currency_equals_base_currency(
    ): void
    {
        $BASE_CURRENCY = 'base_currency';

        $this->transactionMock->method('getCurrency')->willReturn($BASE_CURRENCY);
        $this->transactionMock->method('getAmount')->willReturn(50.00);

        $this->transactionMock->expects($this->never())->method('getBin');

        $exchangeRatesClient = new ExchangeRatesClient(
            client: $this->clientMock,
            requestOption: $this->requestOptionMock,
            baseCurrency: $BASE_CURRENCY,
            apiAccessKey: 'API_KEY'
        );

        $baseValue = $exchangeRatesClient->getBaseValue($this->transactionMock);

        $this->assertEquals($this->transactionMock->getAmount(), $baseValue);
    }
}
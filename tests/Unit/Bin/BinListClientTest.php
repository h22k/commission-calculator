<?php

namespace APP\Tests\Unit\Bin;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use H22k\CommissionCalculator\Bin\Clients\BinListClient;
use H22k\CommissionCalculator\Bin\Enums\EuropeanCountryEnum;
use H22k\CommissionCalculator\RequestOptionInterface;
use H22k\CommissionCalculator\Transaction\Contracts\TransactionInterface;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;

class BinListClientTest extends TestCase
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

    public static function europeanCountriesProvider(): array
    {
        $europeanCountries = array_column(EuropeanCountryEnum::cases(), 'value');
        $provider = [];

        foreach ($europeanCountries as $europeanCountry) {
            $provider[] = [new Response(200, body: sprintf('{"country":{"alpha2": "%s"}}', $europeanCountry))];
        }

        return $provider;
    }

    public static function notEuropeanCountriesProvider(): array
    {
        $notEuropeanCountries = ['TR', 'CH', 'CA'];
        $provider = [];

        foreach ($notEuropeanCountries as $notEuropeanCountry) {
            $provider[] = [new Response(200, body: sprintf('{"country":{"alpha2": "%s"}}', $notEuropeanCountry))];
        }

        return $provider;
    }

    #[DataProvider('europeanCountriesProvider')]
    public function test_iseuropean_method_should_recognize_european_countries(ResponseInterface $response)
    {
        $this->clientMock
            ->expects($this->once())
            ->method('request')
            ->willReturn($response);

        $binlistClient = new BinListClient($this->clientMock, $this->requestOptionMock);
        $this->assertTrue($binlistClient->isEuropean($this->transactionMock));
    }

    #[DataProvider('notEuropeanCountriesProvider')]
    public function test_iseuropean_method_should_recognize_not_european_countries(ResponseInterface $response)
    {
        $this->clientMock
            ->expects($this->once())
            ->method('request')
            ->willReturn($response);
        
        $binlistClient = new BinListClient($this->clientMock, $this->requestOptionMock);
        $this->assertFalse($binlistClient->isEuropean($this->transactionMock));
    }
}
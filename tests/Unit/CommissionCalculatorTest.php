<?php

namespace APP\Tests\Unit;

use H22k\CommissionCalculator\Bin\BaseBinClient;
use H22k\CommissionCalculator\CommissionCalculator;
use H22k\CommissionCalculator\Rate\BaseRateClient;
use H22k\CommissionCalculator\Transaction\Contracts\TransactionInterface;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class CommissionCalculatorTest extends TestCase
{
    private MockObject $binClientMock;
    private MockObject $rateClientMock;
    private MockObject $transactionMock;

    const EUROPEAN_COMMISSION = 0.01;
    const NOT_EUROPEAN_COMMISSION = 0.02;

    protected function setUp(): void
    {
        $this->binClientMock = $this->createMock(BaseBinClient::class);
        $this->rateClientMock = $this->createMock(BaseRateClient::class);
        $this->transactionMock = $this->createMock(TransactionInterface::class);
    }

    public static function commissionRateProvider(): array
    {
        return [
            'european_transaction' => [
                'is_european' => true,
                'base_value' => 150,
                'expected_commission' => round(150 * self::EUROPEAN_COMMISSION, 2)
            ],
            'not_european_transaction' => [
                'is_european' => false,
                'base_value' => 400,
                'expected_commission' => round(400 * self::NOT_EUROPEAN_COMMISSION, 2)
            ]
        ];
    }

    #[DataProvider('commissionRateProvider')]
    public function test_calculate_method_should_calculate_commission_according_to_transaction_type(
        bool $isEuropean,
        float $baseValue,
        float $expectedCommission
    ) {
        $this->binClientMock
            ->expects($this->once())
            ->method('isEuropean')
            ->willReturn($isEuropean);

        $this->rateClientMock
            ->expects($this->once())
            ->method('getBaseValue')
            ->willReturn($baseValue);

        $commissionCalculator = new CommissionCalculator(
            binClient: $this->binClientMock,
            rateClient: $this->rateClientMock,
            europeanCommissionRate: self::EUROPEAN_COMMISSION,
            notEuropeanCommissionRate: self::NOT_EUROPEAN_COMMISSION
        );

        $actualCommission = $commissionCalculator->calculate($this->transactionMock);

        $this->assertEquals($expectedCommission, $actualCommission);
    }
}
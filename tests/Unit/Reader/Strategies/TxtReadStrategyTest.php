<?php

namespace APP\Tests\Unit\Reader\Strategies;

use H22k\CommissionCalculator\Exception\Reader\BadExtensionException;
use H22k\CommissionCalculator\Exception\Reader\BadTransactionException;
use H22k\CommissionCalculator\Reader\File;
use H22k\CommissionCalculator\Reader\Strategies\TxtReadStrategy;
use H22k\CommissionCalculator\Transaction\Contracts\TransactionInterface;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class TxtReadStrategyTest extends TestCase
{
    private MockObject $fileObject;

    public function setUp(): void
    {
        $this->fileObject = $this->createMock(File::class);
    }

    public function test_throw_badextensionexception_if_extension_is_not_txt()
    {
        $NON_TXT_FILE_NAME = 'non_txt_file.json';

        $this->fileObject
            ->expects($this->exactly(2))
            ->method('getFileName')
            ->willReturn($NON_TXT_FILE_NAME);

        $this->expectException(BadExtensionException::class);
        $this->expectExceptionMessage(sprintf('%s is not a .txt file!', $NON_TXT_FILE_NAME));

        new TxtReadStrategy($this->fileObject);
    }

    public static function badTransactionTypeProvider(): array
    {
        return [
            'without_bin' => ['{"amount":"2000.00","currency":"GBP"}'],
            'without_amount' => ['{"bin":"4745030","currency":"GBP"}'],
            'without_currency' => ['{"bin":"4745030","amount":"2000.00"}'],
            'string_amount' => ['{"bin":"4745030","amount":"string","currency":"GBP"}']
        ];
    }

    public static function goodTransactionTypeProvider(): array
    {
        return [
            [
                'amount' => 250.50,
                'currency' => 'EUR',
                'bin' => '345345'
            ]
        ];
    }

    #[DataProvider('goodTransactionTypeProvider')]
    public function test_read_method_should_return_correct_transaction_object(float $amount, string $currency, string $bin): void
    {
        $TXT_FILE = 'some.txt';

        $this->fileObject
            ->method('getFileName')
            ->willReturn($TXT_FILE);


        $this->fileObject
            ->method('readByLine')
            ->willReturnCallback(function () use ($amount, $currency, $bin) {
               yield sprintf('{"bin":"%s","amount":"%.2F","currency":"%s"}', $bin, $amount, $currency);
            });

        $txtReader = new TxtReadStrategy($this->fileObject);

        $transactions = $txtReader->read();

        $this->assertCount(1, $transactions);

        $transaction = $transactions[0];
        $this->assertTrue($transaction instanceof TransactionInterface);

        $this->assertEquals($bin, $transaction->getBin());
        $this->assertEquals($amount, $transaction->getAmount());
        $this->assertEquals($currency, $transaction->getCurrency());
    }

    /**
     * @throws BadExtensionException
     */
    #[DataProvider('badTransactionTypeProvider')]
    public function test_throw_badtransactionexception_if_transaction_is_not_acceptable(string $badTransactionLine)
    {
        $TXT_FILE = 'some.txt';

        $this->fileObject
            ->method('getFileName')
            ->willReturn($TXT_FILE);

        $this->fileObject
            ->method('readByLine')
            ->willReturnCallback(function () use ($badTransactionLine) {
                yield $badTransactionLine;
            });

        $this->expectException(BadTransactionException::class);

        $txtReader = new TxtReadStrategy($this->fileObject);

        $txtReader->read();
    }
}
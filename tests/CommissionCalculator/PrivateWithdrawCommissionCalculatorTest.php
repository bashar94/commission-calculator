<?php

namespace Bashar\CommissionCalculator\Tests\CommissionCalculator;

use Bashar\CommissionCalculator\CommissionCalculator\PrivateWithdrawCommissionCalculator;
use Bashar\CommissionCalculator\CurrencyConverter\CurrencyConverterInterface;
use Bashar\CommissionCalculator\Entity\Client\Client;
use Bashar\CommissionCalculator\Entity\Operation\Operation;
use Bashar\CommissionCalculator\Utils\WeeklyWithdrawalTracker;
use DateTime;
use Exception;
use PHPUnit\Framework\TestCase;

class PrivateWithdrawCommissionCalculatorTest extends TestCase
{
    /**
     * @dataProvider operationsDataProvider
     * @throws Exception
     */
    public function testCalculateWithdrawCommission(array $operationData, string $expectedCommission)
    {
        $client = new Client(1, 'private');
        $operation = new Operation(
            new DateTime($operationData['date']),
            $client,
            'withdraw',
            $operationData['amount'],
            $operationData['decimalPlaces'],
            $operationData['currency']
        );

        // Created Mock CurrencyConverterInterface which will return the converted amount
        $currencyConverter = $this->createMock(CurrencyConverterInterface::class);
        $currencyConverter->method('convert')->willReturnCallback(function ($amount, $fromCurrency, $toCurrency) {
            if ($fromCurrency === $toCurrency) {
                return $amount;
            }
            // Keeping it simple for testing purposes
            return $amount * 1.1;
        });

        // it will store the weekly withdrawal records
        $weeklyWithdrawalTracker = new WeeklyWithdrawalTracker();


        $privateWithdrawCommissionRate = 0.3; // 0.3% for example
        $freeWithdrawCount = 3;
        $freeWithdrawAmount = 1000;
        $baseCurrency = 'EUR';

        $calculator = new PrivateWithdrawCommissionCalculator(
            $privateWithdrawCommissionRate,
            $weeklyWithdrawalTracker,
            $currencyConverter,
            $freeWithdrawCount,
            $freeWithdrawAmount,
            $baseCurrency
        );

        // Call the calculate() method on the calculator
        $commission = $calculator->calculate($operation);

        // Check the result (verify that the calculated commission matches the expected commission)
        $this->assertEquals($expectedCommission, $commission);
    }

    public function operationsDataProvider(): array
    {
        return [
            [
                [
                    'date' => '2023-04-01',
                    'amount' => 500.00,
                    'decimalPlaces' => 2,
                    'currency' => 'USD'
                ],
                '0.00',
            ],
            [
                [
                    'date' => '2023-04-02',
                    'amount' => 1000,
                    'decimalPlaces' => 0,
                    'currency' => 'EUR'
                ],
                '0',
            ],
            [
                [
                    'date' => '2023-05-03',
                    'amount' => 1200.45,
                    'decimalPlaces' => 2,
                    'currency' => 'EUR'
                ],
                '0.61',
            ],
            [
                [
                    'date' => '2023-02-01',
                    'amount' => 100.00,
                    'decimalPlaces' => 2,
                    'currency' => 'EUR'
                ],
                '0.00',
            ],
            [
                [
                    'date' => '2023-02-02',
                    'amount' => 200.00,
                    'decimalPlaces' => 2,
                    'currency' => 'EUR'
                ],
                '0.00',
            ],
            [
                [
                    'date' => '2023-02-03',
                    'amount' => 140.00,
                    'decimalPlaces' => 2,
                    'currency' => 'EUR'
                ],
                '0.00',
            ],
            [
                [
                    'date' => '2023-02-05',
                    'amount' => 40.00,
                    'decimalPlaces' => 2,
                    'currency' => 'EUR'
                ],
                '0.00',
            ]

        ];
    }
}

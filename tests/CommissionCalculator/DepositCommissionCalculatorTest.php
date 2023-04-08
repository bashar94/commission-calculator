<?php

namespace Bashar\CommissionCalculator\Tests\CommissionCalculator;

use Bashar\CommissionCalculator\CommissionCalculator\DepositCommissionCalculator;
use Bashar\CommissionCalculator\Entity\Client\Client;
use Bashar\CommissionCalculator\Entity\Operation\Operation;
use PHPUnit\Framework\TestCase;
use DateTime;

class DepositCommissionCalculatorTest extends TestCase {

    /**
     * @dataProvider depositDataProvider
     * @param float $depositAmount
     * @param int $decimalPlaces
     * @param string $currency
     * @param float $expectedCommission
     */
    public function testCalculate(float $depositAmount, int $decimalPlaces, string $currency, string $expectedCommission) {
        $client = new Client(1, 'private');
        $operation = new Operation(
            new DateTime('2023-04-01'),
            $client,
            'deposit',
            $depositAmount,
            $decimalPlaces,
            $currency
        );

        $depositCommissionRate = 0.03;
        $depositCommissionCalculator = new DepositCommissionCalculator($depositCommissionRate);

        $commission = $depositCommissionCalculator->calculate($operation);

        $this->assertSame($expectedCommission, $commission);
    }

    public function depositDataProvider(): array
    {
        return [
            [200.00, 2, 'EUR', '0.06'],
            [564.00, 2, 'USD', '0.17'],
            [1820, 0, 'EUR', '1']
        ];
    }
}

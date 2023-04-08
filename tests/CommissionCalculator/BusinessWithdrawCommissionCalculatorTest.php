<?php

namespace Bashar\CommissionCalculator\Tests\CommissionCalculator;

use Bashar\CommissionCalculator\CommissionCalculator\BusinessWithdrawCommissionCalculator;
use Bashar\CommissionCalculator\Entity\Client\Client;
use Bashar\CommissionCalculator\Entity\Operation\Operation;
use PHPUnit\Framework\TestCase;
use DateTime;

class BusinessWithdrawCommissionCalculatorTest extends TestCase {

    /**
     * @dataProvider businessWithdrawDataProvider
     * @param float $withdrawAmount
     * @param int $decimalPlaces
     * @param string $currency
     * @param float $expectedCommission
     */
    public function testCalculate(float $withdrawAmount, int $decimalPlaces, string $currency, string $expectedCommission) {
        $client = new Client(1, 'business');
        $operation = new Operation(
            new DateTime('2023-04-01'),
            $client,
            'withdraw',
            $withdrawAmount,
            $decimalPlaces,
            $currency
        );

        $businessWithdrawCommissionRate = 0.5;
        $businessWithdrawCommissionCalculator = new BusinessWithdrawCommissionCalculator($businessWithdrawCommissionRate);

        $commission = $businessWithdrawCommissionCalculator->calculate($operation);

        $this->assertSame($expectedCommission, $commission);
    }

    public function businessWithdrawDataProvider(): array
    {
        return [
            [100.00, 2, 'EUR', '0.50'],
            [2670, 0, 'EUR', '14'],
            [350.00, 2, 'USD', '1.75'],
        ];
    }
}

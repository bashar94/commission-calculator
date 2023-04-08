<?php

namespace Bashar\CommissionCalculator\Tests\Utils;

use Bashar\CommissionCalculator\Utils\CommissionRoundingUtility;
use PHPUnit\Framework\TestCase;

class CommissionRoundingUtilityTest extends TestCase {

    /**
     * @dataProvider roundCommissionDataProvider
     */
    public function testRoundCommission($commission, $decimalPlaces, $expectedRoundedCommission) {
        $roundedCommission = CommissionRoundingUtility::roundCommission($commission, $decimalPlaces);
        $this->assertEquals($expectedRoundedCommission, $roundedCommission);
    }

    public function roundCommissionDataProvider(): array
    {
        return [
            [0.023, 2, 0.03],
            [0.0265, 2, 0.03],
            [0.1, 1, 0.1],
            [0.1234, 3, 0.124],
            [1.005, 2, 1.01],
            [0, 2, 0.00],
            [0, 1, 0.0],
            [0, 0, 0],
            [8612, 0, 8612]
        ];
    }
}

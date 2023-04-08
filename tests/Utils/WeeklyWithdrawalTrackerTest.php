<?php

namespace Bashar\CommissionCalculator\Tests\Utils;

use Bashar\CommissionCalculator\Utils\WeeklyWithdrawalTracker;
use PHPUnit\Framework\TestCase;

class WeeklyWithdrawalTrackerTest extends TestCase {

    /**
     * @dataProvider withdrawalDataProvider
     */
    public function testAddAndGetWithdrawalData($withdrawals, $expectedWithdrawals) {
        $tracker = new WeeklyWithdrawalTracker();

        foreach ($withdrawals as $withdrawal) {
            $tracker->addWithdrawal(
                $withdrawal['client_id'],
                $withdrawal['week_identifier'],
                $withdrawal['amount']
            );
        }

        foreach ($expectedWithdrawals as $clientId => $weekData) {
            foreach ($weekData as $weekIdentifier => $expectedData) {
                $withdrawalData = $tracker->getWithdrawalData($clientId, $weekIdentifier);
                $this->assertEquals($expectedData, $withdrawalData);
            }
        }
    }

    public function withdrawalDataProvider(): array
    {
        return [
            [
                [
                    ['client_id' => 1, 'week_identifier' => '202301', 'amount' => 100.00],
                    ['client_id' => 1, 'week_identifier' => '202301', 'amount' => 200.00],
                ],
                [
                    1 => [
                        '202301' => ['count' => 2, 'amount' => 300.00],
                    ],
                ],
            ],
            [
                [
                    ['client_id' => 1, 'week_identifier' => '202301', 'amount' => 100.00],
                    ['client_id' => 2, 'week_identifier' => '202301', 'amount' => 200.00],
                ],
                [
                    1 => [
                        '202301' => ['count' => 1, 'amount' => 100.00],
                    ],
                    2 => [
                        '202301' => ['count' => 1, 'amount' => 200.00],
                    ],
                ],
            ],
            [
                [
                    ['client_id' => 1, 'week_identifier' => '202301', 'amount' => 100.00],
                    ['client_id' => 1, 'week_identifier' => '202302', 'amount' => 200.00],
                ],
                [
                    1 => [
                        '202301' => ['count' => 1, 'amount' => 100.00],
                        '202302' => ['count' => 1, 'amount' => 200.00],
                    ],
                ],
            ],
        ];
    }
}

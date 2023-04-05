<?php

namespace Bashar\CommissionCalculator\util;

class WeeklyWithdrawalTracker {
    private $withdrawals;

    public function __construct() {
        $this->withdrawals = [];
    }

    public function addWithdrawal(int $clientId, string $weekIdentifier, float $withdrawnAmount) {
        if (!isset($this->withdrawals[$clientId])) {
            $this->withdrawals[$clientId] = [];
        }

        if (!isset($this->withdrawals[$clientId][$weekIdentifier])) {
            $this->withdrawals[$clientId][$weekIdentifier] = [
                'count' => 0,
                'amount' => 0,
            ];
        }

        $this->withdrawals[$clientId][$weekIdentifier]['count']++;
        $this->withdrawals[$clientId][$weekIdentifier]['amount'] += $withdrawnAmount;
    }

    public function getWithdrawalData(int $clientId, string $weekIdentifier): array {
        return $this->withdrawals[$clientId][$weekIdentifier] ?? ['count' => 0, 'amount' => 0];
    }
}

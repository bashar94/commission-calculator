<?php

namespace Bashar\CommissionCalculator\Utils;


/**
 * This class is a utility to track weekly withdrawals for clients.
 * It stores the number of withdrawals and the total withdrawn amount for each client and week.
 */
class WeeklyWithdrawalTracker {
    private $withdrawals;

    public function __construct() {
        $this->withdrawals = [];
    }

    /**
     * Adds a withdrawal to the tracker.
     *
     * @param int $clientId The client ID
     * @param string $weekIdentifier The week identifier ('202301' = first week of 2023)
     * @param float $withdrawnAmount The amount withdrawn
     */
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

    /**
     * Retrieves the withdrawal data for a specific client on a specific week.
     *
     * @param int $clientId The client ID
     * @param string $weekIdentifier The week identifier ('202301' = first week of 2023)
     * @return array The withdrawal data for the client on that week
     */
    public function getWithdrawalData(int $clientId, string $weekIdentifier): array {
        return $this->withdrawals[$clientId][$weekIdentifier] ?? ['count' => 0, 'amount' => 0];
    }
}

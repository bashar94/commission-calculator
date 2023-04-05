<?php

namespace Bashar\CommissionCalculator\CommissionCalculator;

use Bashar\CommissionCalculator\Entity\Operation\Operation;
use Bashar\CommissionCalculator\Utils\CommissionRoundingUtility;

class BusinessWithdrawCommissionCalculator extends WithdrawCommissionCalculator {
    private $businessWithdrawCommissionRate;

    /**
     * @param float $businessWithdrawCommissionRate The business withdrawal commission rate as percentage.
     */
    public function __construct(float $businessWithdrawCommissionRate) {
        $this->businessWithdrawCommissionRate = ($businessWithdrawCommissionRate / 100);
    }

    /**
     * Calculates the commission for a business withdraw operation.
     *
     * @param Operation $operation The business withdraw operation
     * @return float The calculated commission
     */
    protected function calculateWithdrawCommission(Operation $operation): float {
        // Calculating the commission for business withdraw operations
        $commission = $operation->getAmount() * $this->businessWithdrawCommissionRate;
        return CommissionRoundingUtility::roundCommission($commission, $operation->getDecimalPlaces());
    }
}

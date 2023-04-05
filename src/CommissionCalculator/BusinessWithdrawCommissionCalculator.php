<?php

namespace Bashar\CommissionCalculator\CommissionCalculator;

use Bashar\CommissionCalculator\Operation\Operation;
use Bashar\CommissionCalculator\util\CommissionRoundingUtility;

class BusinessWithdrawCommissionCalculator extends WithdrawCommissionCalculator {
    private $businessWithdrawCommissionRate;

    public function __construct(float $businessWithdrawCommissionRate) {
        $this->businessWithdrawCommissionRate = ($businessWithdrawCommissionRate / 100);
    }

    protected function calculateWithdrawCommission(Operation $operation): float {
        // Implement the logic for calculating the commission for business withdraw operations
        $commission = $operation->getAmount() * $this->businessWithdrawCommissionRate;
        return CommissionRoundingUtility::roundCommission($commission, $operation->getDecimalPlaces());
    }
}

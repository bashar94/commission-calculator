<?php

namespace Bashar\CommissionCalculator\CommissionCalculator;

use Bashar\CommissionCalculator\Entity\Operation\Operation;
use Bashar\CommissionCalculator\Utils\CommissionRoundingUtility;

class BusinessWithdrawCommissionCalculator extends WithdrawCommissionCalculator {
    private $businessWithdrawCommissionRate;

    public function __construct(float $businessWithdrawCommissionRate) {
        $this->businessWithdrawCommissionRate = ($businessWithdrawCommissionRate / 100);
    }

    protected function calculateWithdrawCommission(Operation $operation): float {
        // Calculating the commission for business withdraw operations
        $commission = $operation->getAmount() * $this->businessWithdrawCommissionRate;
        return CommissionRoundingUtility::roundCommission($commission, $operation->getDecimalPlaces());
    }
}

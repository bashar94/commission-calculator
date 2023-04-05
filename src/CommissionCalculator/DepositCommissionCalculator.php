<?php

namespace Bashar\CommissionCalculator\CommissionCalculator;

use Bashar\CommissionCalculator\Entity\Operation\Operation;
use Bashar\CommissionCalculator\Utils\CommissionRoundingUtility;

class DepositCommissionCalculator implements CommissionCalculatorInterface {
    private $depositCommissionRate;

    public function __construct(float $depositCommissionRate) {
        $this->depositCommissionRate = ($depositCommissionRate / 100);
    }

    public function calculate(Operation $operation): float {
        $commission = $operation->getAmount() * $this->depositCommissionRate;
        return CommissionRoundingUtility::roundCommission($commission, $operation->getDecimalPlaces());
    }
}

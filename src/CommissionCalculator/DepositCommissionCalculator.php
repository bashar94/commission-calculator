<?php

namespace Bashar\CommissionCalculator\CommissionCalculator;

use Bashar\CommissionCalculator\Entity\Operation\Operation;
use Bashar\CommissionCalculator\Utils\CommissionRoundingUtility;

class DepositCommissionCalculator implements CommissionCalculatorInterface {
    private $depositCommissionRate;

    /**
     * @param float $depositCommissionRate The deposit commission rate as percentage
     */
    public function __construct(float $depositCommissionRate) {
        $this->depositCommissionRate = ($depositCommissionRate / 100);
    }

    /**
     * Calculates the commission for the deposit operation.
     *
     * @param Operation $operation The operation for which the commission needs to be calculated.
     * @return float The calculated commission for the deposit operation.
     */
    public function calculate(Operation $operation): float {
        $commission = $operation->getAmount() * $this->depositCommissionRate;
        return CommissionRoundingUtility::roundCommission($commission, $operation->getDecimalPlaces());
    }
}

<?php

namespace Bashar\CommissionCalculator\CommissionCalculator;

use Bashar\CommissionCalculator\Entity\Operation\Operation;

abstract class WithdrawCommissionCalculator implements CommissionCalculatorInterface {
    public function calculate(Operation $operation): string {
        return $this->calculateWithdrawCommission($operation);
    }

    abstract protected function calculateWithdrawCommission(Operation $operation): string;
}

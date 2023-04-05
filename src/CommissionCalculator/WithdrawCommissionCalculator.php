<?php

namespace Bashar\CommissionCalculator\CommissionCalculator;

use Bashar\CommissionCalculator\Operation\Operation;

abstract class WithdrawCommissionCalculator implements CommissionCalculatorInterface {
    public function calculate(Operation $operation): float {
        return $this->calculateWithdrawCommission($operation);
    }

    abstract protected function calculateWithdrawCommission(Operation $operation): float;
}

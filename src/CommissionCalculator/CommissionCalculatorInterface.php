<?php

namespace Bashar\CommissionCalculator\CommissionCalculator;

use Bashar\CommissionCalculator\Operation\Operation;

interface CommissionCalculatorInterface {
    public function calculate(Operation $operation): float;
}

<?php

namespace Bashar\CommissionCalculator\CommissionCalculator;

use Bashar\CommissionCalculator\Entity\Operation\Operation;

interface CommissionCalculatorInterface {
    public function calculate(Operation $operation): float;
}

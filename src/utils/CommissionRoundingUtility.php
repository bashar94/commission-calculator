<?php

namespace Bashar\CommissionCalculator\utils;

class CommissionRoundingUtility {
    public static function roundCommission(float $commission, int $decimalPlaces): float {
        $multiplier = pow(10, $decimalPlaces);
        return ceil($commission * $multiplier) / $multiplier;
    }
}

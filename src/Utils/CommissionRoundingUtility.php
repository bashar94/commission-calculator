<?php

namespace Bashar\CommissionCalculator\Utils;

class CommissionRoundingUtility {

    /**
     * Rounds up the commission value to the specified number of decimal places.
     *
     * @param float $commission The commission value to be rounded up.
     * @param int $decimalPlaces The number of decimal places to round up to (CsvReader class has the calculation and
     * storing it in Operation entity).
     * @return string The rounded-up commission value.
     */

    public static function roundCommission(float $commission, int $decimalPlaces): string {
        $multiplier = pow(10, $decimalPlaces);
        $roundedCommission = ceil($commission * $multiplier) / $multiplier;
        return sprintf("%." . $decimalPlaces . "f", $roundedCommission);
    }

}

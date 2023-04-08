<?php

namespace Bashar\CommissionCalculator\CurrencyConverter;

interface CurrencyConverterInterface {
    public function convert(float $amount, string $fromCurrency, string $toCurrency): float;
    public function fetchExchangeRates(): array;
}

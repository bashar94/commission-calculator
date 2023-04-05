<?php

namespace Bashar\CommissionCalculator\CurrencyConverter;

use Exception;

class CurrencyConverter implements CurrencyConverterInterface {

    private $exchangeRates;

    /**
     * @throws Exception
     */
    public function __construct() {
        $apiUrl = getenv('API_URL');
        if (!$apiUrl) {
            throw new Exception("API_URL not found in .env file.");
        }
        $this->exchangeRates = $this->fetchExchangeRates($apiUrl);
    }

    public function convert(float $amount, string $fromCurrency, string $toCurrency): float {
        if ($fromCurrency === $toCurrency) {
            return $amount;
        }

        $rate = $this->exchangeRates[$fromCurrency] / $this->exchangeRates[$toCurrency];
        return $amount * $rate;
    }

    /**
     * @throws Exception
     */
    public function fetchExchangeRates(string $apiUrl): array {
        $response = file_get_contents($apiUrl);
        $data = json_decode($response, true);

        if (!isset($data['rates'])) {
            throw new Exception("Error fetching exchange rates.");
        }

        return $data['rates'];
    }
}

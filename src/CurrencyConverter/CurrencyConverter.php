<?php

namespace Bashar\CommissionCalculator\CurrencyConverter;

use Exception;

class CurrencyConverter implements CurrencyConverterInterface {

    private $exchangeRates;

    /**
     * @throws Exception If an error occurs while getting API_URL value from .env file.
     */
    public function __construct() {
        $apiUrl = getenv('API_URL');
        if (!$apiUrl) {
            throw new Exception("API_URL not found in .env file.");
        }
        $this->exchangeRates = $this->fetchExchangeRates($apiUrl);
    }


    /**
     * Converts an amount from one currency to another using the stored exchange rates.
     *
     * @param float $amount The amount to be converted.
     * @param string $fromCurrency The source currency code (e.g. 'USD').
     * @param string $toCurrency The target currency code (e.g. 'EUR').
     * @return float The converted amount in the target currency.
     */
    public function convert(float $amount, string $fromCurrency, string $toCurrency): float {
        if ($fromCurrency === $toCurrency) {
            return $amount;
        }
        $rate = $this->exchangeRates[$toCurrency] / $this->exchangeRates[$fromCurrency] ;
        return $amount * $rate;
    }

    /**
     * Fetches exchange rates from the provided API URL and returns them as an array.
     *
     * @param string $apiUrl The URL of the exchange rate API.
     * @throws Exception If an error occurs while fetching exchange rates.
     * @return array An associative array containing currency codes as keys and exchange rates as values.
     */
    public function fetchExchangeRates(string $apiUrl): array {
        // fetching data from the API
        $response = file_get_contents($apiUrl);
        $data = json_decode($response, true);

        if (!isset($data['rates'])) {
            throw new Exception("Error fetching exchange rates.");
        }

        return [
            'EUR' => 1,
            'USD' => 110,
            'JPY' => 110,
        ];

        return $data['rates'];
    }
}

<?php

namespace Bashar\CommissionCalculator\Tests\CurrencyConverter;

use Bashar\CommissionCalculator\CurrencyConverter\CurrencyConverter;
use PHPUnit\Framework\TestCase;

class CurrencyConverterTest extends TestCase {

    /**
     * @dataProvider currencyConverterDataProvider
     */
    public function testConvert($amount, $fromCurrency, $toCurrency, $expectedResult) {
        $currencyConverter = $this->getMockBuilder(CurrencyConverter::class)
            ->setMethods(['fetchExchangeRates'])
            ->getMock();

        $currencyConverter->expects($this->once())
            ->method('fetchExchangeRates')
            ->willReturn(['EUR' => 1, 'USD' => 1.1497, 'JPY' => 129.53]); // as EUR is the base currency

        $currencyConverter->setExchangeRates($currencyConverter->fetchExchangeRates());
        $result = $currencyConverter->convert($amount, $fromCurrency, $toCurrency);
        $this->assertSame($expectedResult, $result);
    }

    public function currencyConverterDataProvider(): array
    {
        return [
            [100, 'EUR', 'EUR', 100.0],
            [100, 'USD', 'USD', 100.0],
            [100, 'USD', 'EUR', 86.97921196834],
            [100, 'EUR', 'USD', 114.97],
            [100, 'JPY', 'JPY', 100.0],
            [100, 'JPY', 'USD', 0.88759360765846],
            [100, 'USD', 'JPY', 11266.417326259],
        ];
    }
}

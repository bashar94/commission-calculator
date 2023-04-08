<?php

namespace Bashar\CommissionCalculator\Tests\Entity\Operation;

use Bashar\CommissionCalculator\Entity\Client\Client;
use Bashar\CommissionCalculator\Entity\Operation\Operation;
use DateTime;
use Exception;
use PHPUnit\Framework\TestCase;

class OperationTest extends TestCase {

    /**
     * @dataProvider operationDataProvider
     * @throws Exception
     */
    public function testGetters($date, $clientType, $operationType, $amount, $decimalPlaces, $currency) {
        $date = new DateTime($date);
        $client = new Client(1, $clientType);
        $operation = new Operation($date, $client, $operationType, $amount, $decimalPlaces, $currency);

        $this->assertSame($date, $operation->getDate());
        $this->assertSame($client, $operation->getClient());
        $this->assertSame($operationType, $operation->getType());
        $this->assertEquals($amount, $operation->getAmount());
        $this->assertSame($decimalPlaces, $operation->getDecimalPlaces());
        $this->assertSame($currency, $operation->getCurrency());
    }

    public function operationDataProvider(): array
    {
        return [
            ['2023-04-01', 'private', 'deposit', 100, 2, 'USD'],
            ['2023-04-02', 'private', 'withdraw', 50, 2, 'USD'],
            ['2023-04-03', 'business', 'deposit', 150, 3, 'EUR'],
            ['2023-04-04', 'business', 'withdraw', 75, 3, 'EUR'],
            ['2023-04-05', 'private', 'deposit', 200, 2, 'GBP'],
        ];
    }
}

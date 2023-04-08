<?php

namespace Bashar\CommissionCalculator\Tests\Entity\Client;

use Bashar\CommissionCalculator\Entity\Client\Client;
use PHPUnit\Framework\TestCase;

class ClientTest extends TestCase {

    /**
     * @dataProvider clientDataProvider
     */
    public function testGetIdAndType($id, $type) {
        $client = new Client($id, $type);
        $this->assertSame($id, $client->getId());
        $this->assertSame($type, $client->getType());
    }

    public function clientDataProvider(): array
    {
        return [
            [1, 'private'],
            [2, 'business'],
            [3, 'private'],
            [4, 'business'],
            [5, 'private'],
        ];
    }
}

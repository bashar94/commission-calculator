<?php

namespace Bashar\CommissionCalculator\Tests\CsvReader;

use Bashar\CommissionCalculator\CsvReader\CsvReader;
use Exception;
use PHPUnit\Framework\TestCase;

class CsvReaderTest extends TestCase {

    /**
     * @dataProvider csvReaderDataProvider
     * @param string $inputFile
     * @param int $expectedCount
     * @throws Exception
     */
    public function testRead(string $inputFile, int $expectedCount) {
        $csvReader = new CsvReader();
        $operations = $csvReader->read(__DIR__ . '/' . $inputFile);
        $this->assertCount($expectedCount, $operations);
    }

    public function csvReaderDataProvider(): array
    {
        return [
            ['input_13.csv', 13],
            ['input_2.csv', 2],
        ];
    }

    public function testRead_invalidFile() {
        $csvReader = new CsvReader();
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Error opening CSV file.");
        $csvReader->read(__DIR__ . '/non_existing_file.csv');
    }

    public function testRead_emptyFile() {
        $csvReader = new CsvReader();
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Empty CSV file.");
        $csvReader->read(__DIR__ . '/input_0.csv');
    }
}

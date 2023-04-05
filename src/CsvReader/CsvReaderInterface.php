<?php

namespace Bashar\CommissionCalculator\CsvReader;

interface CsvReaderInterface {
    public function read(string $filename): array;
}

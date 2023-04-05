<?php

namespace Bashar\CommissionCalculator\CsvReader;

use Bashar\CommissionCalculator\Client\Client;
use Bashar\CommissionCalculator\Operation\Operation;
use Exception;

class CsvReader implements CsvReaderInterface {
    /**
     * @throws Exception
     */
    public function read(string $filename, array $header = null): array {
        $file = fopen($filename, 'r');
        if (!$file) {
            throw new Exception("Error opening CSV file.");
        }

        if ($header === null) {
            $header = fgetcsv($file);

            // Check if all the columns in the first row are strings
            $isHeader = true;
            foreach ($header as $column) {
                if (is_numeric($column)) {
                    $isHeader = false;
                    break;
                }
            }

            // If the first row is not a header, set default headers and rewind the file
            if (!$isHeader) {
                $header = [
                    'operation_date',
                    'user_identificator',
                    'user_type',
                    'operation_type',
                    'operation_amount',
                    'operation_currency',
                ];

                rewind($file);
            }
        }

        $operations = [];

        while (($row = fgetcsv($file)) !== false) {
            $rowData = array_combine($header, $row);
            $client = new Client((int)$rowData['user_identificator'], $rowData['user_type']);
            $operationAmount = (float)$rowData['operation_amount'];
            $decimalPlaces = strlen(substr(strrchr((string)$operationAmount, "."), 1));
            $operation = new Operation(
                $rowData['operation_date'],
                $client,
                $rowData['operation_type'],
                $operationAmount,
                $decimalPlaces,
                $rowData['operation_currency']
            );

            $operations[] = $operation;
        }

        fclose($file);

        return $operations;
    }
}

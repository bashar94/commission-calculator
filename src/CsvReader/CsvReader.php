<?php

namespace Bashar\CommissionCalculator\CsvReader;

use Bashar\CommissionCalculator\Entity\Client\Client;
use Bashar\CommissionCalculator\Entity\Operation\Operation;
use DateTime;
use Exception;

class CsvReader implements CsvReaderInterface {

    /**
     * Reads the specified CSV file and returns an array of Operation objects.
     *
     * @param string $filename The path to the CSV file.
     * @param array|null $header An optional array of header column names.
     * @throws Exception If an error occurs while opening the file.
     * @return Operation[] An array of Operation objects.
     */
    public function read(string $filename, array $header = null): array {
        // Opening the CSV file for reading
        try{
            $file = fopen($filename, 'r');
        } catch (Exception $exception) {
            throw new Exception("Error opening CSV file.");
        }

        // If no header is given then checking if the first row of the file is the header
        if ($header === null) {
            $header = fgetcsv($file);

            if(!$header) {
                throw new Exception("Empty CSV file.");
            }

            // Checking if all the columns in the first row are strings
            $isHeader = true;
            foreach ($header as $column) {
                if (is_numeric($column)) {
                    $isHeader = false;
                    break;
                }
            }

            // If the first row is not a header then setting default headers and rewind the file
            // it will make things easier
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

        // Looping through each row of the CSV file and create an Operation object for each one
        while (($row = fgetcsv($file)) !== false) {
            // Mapping the row values with respective headers (sequentially)
            $rowData = array_combine($header, $row);

            // Creating a DateTime object based on the operation_date column
            $operationDate = new DateTime($rowData['operation_date']);

            // Creating a Client object based on the user_identificator and user_type columns
            $client = new Client((int)$rowData['user_identificator'], $rowData['user_type']);

            $operationAmount = (float)$rowData['operation_amount'];

            // Create an Operation object based on the row data
            $operation = new Operation(
                $operationDate,
                $client,
                $rowData['operation_type'],
                $operationAmount,
                $this->getDecimalPlaces($rowData['operation_amount']),
                $rowData['operation_currency']
            );

            // Add the Operation object to the operations array
            $operations[] = $operation;
        }

        // Close the CSV file
        fclose($file);

        // Return the array of Operation objects
        return $operations;
    }

    function getDecimalPlaces($amount): int {
        $decimalPointPosition = strpos($amount, '.');
        if ($decimalPointPosition !== false) {
            return strlen(substr($amount, $decimalPointPosition + 1));
        } else {
            return 0; // there are no decimal places
        }
    }
}

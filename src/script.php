<?php

use Bashar\CommissionCalculator\CommissionCalculator\BusinessWithdrawCommissionCalculator;
use Bashar\CommissionCalculator\CommissionCalculator\DepositCommissionCalculator;
use Bashar\CommissionCalculator\CommissionCalculator\PrivateWithdrawCommissionCalculator;
use Bashar\CommissionCalculator\CsvReader\CsvReader;
use Bashar\CommissionCalculator\CurrencyConverter\CurrencyConverter;
use Bashar\CommissionCalculator\util\WeeklyWithdrawalTracker;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();


$csvReader = new CsvReader();
try {
    $operations = $csvReader->read('input.csv');
} catch (Exception $exception) {
    echo $exception->getMessage();
    exit;
}

$currencyConverter = new CurrencyConverter();
$depositCommissionCalculator = new DepositCommissionCalculator(0.03);
$weeklyWithdrawalTracker = new WeeklyWithdrawalTracker();
$currencyConverter = new CurrencyConverter();
$privateWithdrawCommissionCalculator = new PrivateWithdrawCommissionCalculator(0.3, $weeklyWithdrawalTracker, $currencyConverter);
$businessWithdrawCommissionCalculator = new BusinessWithdrawCommissionCalculator(0.5);


foreach ($operations as $operation) {
    $clientType = $operation->getClient()->getType();
    $operationType = $operation->getType();

    if ($operationType === 'deposit') {
        $commission = $depositCommissionCalculator->calculate($operation);
    } elseif ($operationType === 'withdraw') {
        if ($clientType === 'private') {
            $commission = $privateWithdrawCommissionCalculator->calculate($operation);
        } else {
            $commission = $businessWithdrawCommissionCalculator->calculate($operation);
        }
    }

    echo $commission . PHP_EOL;
}
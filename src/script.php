<?php

use Bashar\CommissionCalculator\CommissionCalculator\BusinessWithdrawCommissionCalculator;
use Bashar\CommissionCalculator\CommissionCalculator\DepositCommissionCalculator;
use Bashar\CommissionCalculator\CommissionCalculator\PrivateWithdrawCommissionCalculator;
use Bashar\CommissionCalculator\CsvReader\CsvReader;
use Bashar\CommissionCalculator\CurrencyConverter\CurrencyConverter;
use Bashar\CommissionCalculator\Entity\Client\ClientType;
use Bashar\CommissionCalculator\Entity\Operation\OperationType;
use Bashar\CommissionCalculator\utils\WeeklyWithdrawalTracker;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$privateWithdrawCommissionRate = (float) getenv('PRIVATE_WITHDRAW_COMMISSION_RATE');
$businessWithdrawCommissionRate = (float) getenv('BUSINESS_WITHDRAW_COMMISSION_RATE');
$depositCommissionRate = (float) getenv('DEPOSIT_COMMISSION_RATE');

$freeWithdrawCount = (int) getenv('FREE_WITHDRAW_COUNT');
$freeWithdrawAmount = (float) getenv('FREE_WITHDRAW_AMOUNT');
$baseCurrency = getenv('BASE_CURRENCY');


$csvReader = new CsvReader();
try {
    $operations = $csvReader->read('input.csv');
} catch (Exception $exception) {
    echo $exception->getMessage();
    exit;
}

$currencyConverter = new CurrencyConverter();
$weeklyWithdrawalTracker = new WeeklyWithdrawalTracker();
$currencyConverter = new CurrencyConverter();

$depositCommissionCalculator = new DepositCommissionCalculator($depositCommissionRate);
$privateWithdrawCommissionCalculator = new PrivateWithdrawCommissionCalculator(
    $privateWithdrawCommissionRate,
    $weeklyWithdrawalTracker,
    $currencyConverter,
    $freeWithdrawCount,
    $freeWithdrawAmount,
    $baseCurrency
);

$businessWithdrawCommissionCalculator = new BusinessWithdrawCommissionCalculator($businessWithdrawCommissionRate);


foreach ($operations as $operation) {
    $clientType = $operation->getClient()->getType();
    $operationType = $operation->getType();

    if ($operationType === OperationType::DEPOSIT) {
        $commission = $depositCommissionCalculator->calculate($operation);
    } elseif ($operationType === OperationType::WITHDRAW) {
        if ($clientType === ClientType::PRIVATE) {
            $commission = $privateWithdrawCommissionCalculator->calculate($operation);
        } else {
            $commission = $businessWithdrawCommissionCalculator->calculate($operation);
        }
    }

    echo $commission . PHP_EOL;
}

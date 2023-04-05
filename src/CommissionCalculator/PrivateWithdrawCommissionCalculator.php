<?php

namespace Bashar\CommissionCalculator\CommissionCalculator;

use Bashar\CommissionCalculator\CurrencyConverter\CurrencyConverterInterface;
use Bashar\CommissionCalculator\Operation\Operation;
use Bashar\CommissionCalculator\utils\WeeklyWithdrawalTracker;
use Bashar\CommissionCalculator\utils\CommissionRoundingUtility;

class PrivateWithdrawCommissionCalculator extends WithdrawCommissionCalculator {
    private $privateWithdrawCommissionRate;
    private $weeklyWithdrawalTracker;
    private $currencyConverter;

    public function __construct(float $privateWithdrawCommissionRate, WeeklyWithdrawalTracker $weeklyWithdrawalTracker, CurrencyConverterInterface $currencyConverter) {
        $this->privateWithdrawCommissionRate = ($privateWithdrawCommissionRate / 100);
        $this->weeklyWithdrawalTracker = $weeklyWithdrawalTracker;
        $this->currencyConverter = $currencyConverter;
    }

    protected function calculateWithdrawCommission(Operation $operation): float {
        $clientId = $operation->getClient()->getId();
        $weekIdentifier = $operation->getDate()->format('oW');

        // TODO: Need to change currency hard-coding and numeric dependencies
        $withdrawnAmountEur = $operation->getCurrency() === 'EUR' ? $operation->getAmount() : $this->currencyConverter->convert($operation->getAmount(), $operation->getCurrency(), 'EUR');
        $withdrawalData = $this->weeklyWithdrawalTracker->getWithdrawalData($clientId, $weekIdentifier);
        $this->weeklyWithdrawalTracker->addWithdrawal($clientId, $weekIdentifier, $withdrawnAmountEur);

        if ($withdrawalData['count'] < 3) {
            $remainingFreeAmount = 1000 - $withdrawalData['amount'];
            if ($remainingFreeAmount >= $withdrawnAmountEur) {
                $commission = 0;
            } else {
                $commission = max($operation->getAmount() - $this->currencyConverter->convert($remainingFreeAmount, 'EUR', $operation->getCurrency()), 0) * $this->privateWithdrawCommissionRate;
            }
        }else{
            $commission = $operation->getAmount() * $this->privateWithdrawCommissionRate;
        }

        return CommissionRoundingUtility::roundCommission($commission, $operation->getDecimalPlaces());
    }
}

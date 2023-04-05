<?php

namespace Bashar\CommissionCalculator\CommissionCalculator;

use Bashar\CommissionCalculator\CurrencyConverter\CurrencyConverterInterface;
use Bashar\CommissionCalculator\Entity\Operation\Operation;
use Bashar\CommissionCalculator\Utils\CommissionRoundingUtility;
use Bashar\CommissionCalculator\Utils\WeeklyWithdrawalTracker;

class PrivateWithdrawCommissionCalculator extends WithdrawCommissionCalculator
{
    private $privateWithdrawCommissionRate;
    private $weeklyWithdrawalTracker;
    private $currencyConverter;
    private $freeWithdrawCount;
    private $freeWithdrawAmount;
    private $baseCurrency;

    public function __construct(
        float $privateWithdrawCommissionRate,
        WeeklyWithdrawalTracker $weeklyWithdrawalTracker,
        CurrencyConverterInterface $currencyConverter,
        int $freeWithdrawCount,
        float $freeWithdrawAmount,
        string $baseCurrency
    ) {
        $this->privateWithdrawCommissionRate = ($privateWithdrawCommissionRate / 100);
        $this->weeklyWithdrawalTracker = $weeklyWithdrawalTracker;
        $this->currencyConverter = $currencyConverter;
        $this->freeWithdrawCount = $freeWithdrawCount;
        $this->freeWithdrawAmount = $freeWithdrawAmount;
        $this->baseCurrency = $baseCurrency;
    }

    protected function calculateWithdrawCommission(Operation $operation): float
    {
        $clientId = $operation->getClient()->getId();
        $weekIdentifier = $operation->getDate()->format('oW');

        // Checking if the operation currency is the base currency (EUR) and calculating the withdrawn amount in the base currency
        // Otherwise, convert the withdrawn amount to the base currency
        $isBaseCurrency = $operation->getCurrency() === $this->baseCurrency;
        $withdrawnAmountBaseCurrency = $isBaseCurrency
            ? $operation->getAmount()
            : $this->currencyConverter->convert(
                $operation->getAmount(),
                $operation->getCurrency(),
                $this->baseCurrency
            );

        // Getting the withdrawal data for the client and the week, and update the tracker with the new withdrawal
        $withdrawalData = $this->weeklyWithdrawalTracker->getWithdrawalData($clientId, $weekIdentifier);
        $this->weeklyWithdrawalTracker->addWithdrawal($clientId, $weekIdentifier, $withdrawnAmountBaseCurrency);

        $commission = 0;
        // Calculating the commission based on the withdrawal count (3) and the remaining free amount limit (1000)
        if ($withdrawalData['count'] < $this->freeWithdrawCount) {
            // if the withdrawal count is less than the free withdrawal count, calculate the remaining free amount
            $remainingFreeAmount = $this->freeWithdrawAmount - $withdrawalData['amount'];
            $isWithinFreeAmount = $remainingFreeAmount >= $withdrawnAmountBaseCurrency;

            if (!$isWithinFreeAmount) {
                // if not within the free amount limit, calculate the commission based on the excess amount
                // and convert the excess amount to the operation currency
                $excessAmount = $operation->getAmount() - $this->currencyConverter->convert($remainingFreeAmount, $this->baseCurrency, $operation->getCurrency());
                $commission = max($excessAmount, 0) * $this->privateWithdrawCommissionRate;
            }
        } else {
            // if more than 3 withdrawal count then regular commission calculation
            $commission = $operation->getAmount() * $this->privateWithdrawCommissionRate;
        }

        // Rounding the calculated commission and returning it
        return CommissionRoundingUtility::roundCommission($commission, $operation->getDecimalPlaces());

    }
}

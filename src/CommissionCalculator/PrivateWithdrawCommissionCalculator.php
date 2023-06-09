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

    /**
     * @param float $privateWithdrawCommissionRate The private withdrawal commission rate as a percentage.
     * @param WeeklyWithdrawalTracker $weeklyWithdrawalTracker An instance of the WeeklyWithdrawalTracker to track weekly withdrawals
     * @param CurrencyConverterInterface $currencyConverter An instance of the CurrencyConverterInterface to convert between currencies
     * @param int $freeWithdrawCount The free withdrawals limit per week (3)
     * @param float $freeWithdrawAmount The limit of free withdrawal amount allowed per week (1000)
     * @param string $baseCurrency The base currency used for conversions and calculations ('EUR')
     */
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

    /**
     * Calculates the withdrawal commission for private clients based on the given operation.
     * Considers the free withdrawal count and free withdrawal amount limit.
     *
     * @param Operation $operation The operation for which the commission needs to be calculated.
     * @return string The calculated commission for the withdrawal operation.
     */

    protected function calculateWithdrawCommission(Operation $operation): string
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
                // if not within the free amount limit, calculate the commission for the excess amount
                if($remainingFreeAmount >= 0){
                    // if the remaining free amount is greater than 0, then we can subtract the remaining free amount
                    $excessAmount = $operation->getAmount() - $this->currencyConverter->convert($remainingFreeAmount, $this->baseCurrency, $operation->getCurrency());
                }else{
                    // It doesn't have any remaining free amount, so the excess amount is the same as the withdrawn amount
                    $excessAmount = $operation->getAmount();
                }
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

<?php

namespace Bashar\CommissionCalculator\Entity\Operation;

use Bashar\CommissionCalculator\Entity\Client\Client;
use DateTime;

class Operation implements OperationInterface {
    private $date;
    private $client;
    private $type;
    private $amount;
    private $decimalPlaces;
    private $currency;

    public function __construct(DateTime $date, Client $client, string $type, float $amount, int $decimalPlaces, string $currency) {
        $this->date = $date;
        $this->client = $client;
        $this->type = $type;
        $this->amount = $amount;
        $this->decimalPlaces = $decimalPlaces;
        $this->currency = $currency;
    }

    public function getDate(): DateTime {
        return $this->date;
    }

    public function getClient(): Client {
        return $this->client;
    }

    public function getType(): string {
        return $this->type;
    }

    public function getAmount(): float {
        return $this->amount;
    }

    public function getDecimalPlaces(): int {
        return $this->decimalPlaces;
    }

    public function getCurrency(): string {
        return $this->currency;
    }
}

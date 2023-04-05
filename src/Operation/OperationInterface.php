<?php

namespace Bashar\CommissionCalculator\Operation;

use DateTime;
use Bashar\CommissionCalculator\Client\Client;

interface OperationInterface {
    public function getDate(): DateTime;
    public function getClient(): Client;
    public function getType(): string;
    public function getAmount(): float;
    public function getCurrency(): string;
}

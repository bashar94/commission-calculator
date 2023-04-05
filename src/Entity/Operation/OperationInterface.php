<?php

namespace Bashar\CommissionCalculator\Entity\Operation;

use Bashar\CommissionCalculator\Entity\Client\Client;
use DateTime;

interface OperationInterface {
    public function getDate(): DateTime;
    public function getClient(): Client;
    public function getType(): string;
    public function getAmount(): float;
    public function getCurrency(): string;
}

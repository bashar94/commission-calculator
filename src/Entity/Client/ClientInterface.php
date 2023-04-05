<?php

namespace Bashar\CommissionCalculator\Entity\Client;

interface ClientInterface {
    public function getId(): int;
    public function getType(): string;
}

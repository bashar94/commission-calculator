<?php

namespace Bashar\CommissionCalculator\Client;

class Client {
    private $id;
    private $type;

    public function __construct(int $id, string $type) {
        $this->id = $id;
        $this->type = $type;
    }

    public function getId(): int {
        return $this->id;
    }

    public function getType(): string {
        return $this->type;
    }
}

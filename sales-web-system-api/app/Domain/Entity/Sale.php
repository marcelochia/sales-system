<?php

namespace App\Domain\Entity;

use DateTime;

class Sale
{
    public function __construct(
        private float $value,
        private DateTime $date,
        private Seller $seller,
        private ?int $id = null,
        private float $commission = 0
    ) {}

    public function getId(): int
    {
        return $this->id;
    }

    /** @throws \LogicException se o ID não for nulo */
    public function setId(int $id): self
    {
        if (!is_null($this->id)) {
            throw new \LogicException('O ID já foi definido para esse objeto.');
        }

        $this->id = $id;

        return $this;
    }

    public function getValue(): float
    {
        return $this->value;
    }

    public function getDate(): DateTime
    {
        return $this->date;
    }

    public function getSeller(): Seller
    {
        return $this->seller;
    }

    public function getCommission(): float
    {
        return $this->commission;
    }

    public function setCommission(float $commission): self
    {
        $this->commission = $commission;

        return $this;
    }
}

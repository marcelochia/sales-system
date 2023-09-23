<?php

namespace App\Domain\Entity;

use App\Domain\ValueObjects\Email;

class Seller
{
    public function __construct(private string $name, private Email $email, private ?int $id = null) {}

    public function getId(): ?int
    {
        return $this->id;
    }

    /** @throws \DomainException se o ID não for nulo */
    public function setId(int $id): self
    {
        if (!is_null($this->id)) {
            throw new \DomainException('O ID já foi definido para esse objeto.');
        }

        $this->id = $id;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getEmail(): string
    {
        return $this->email->email;
    }

    public function setEmail(Email $email): self
    {
        $this->email = $email;

        return $this;
    }
}

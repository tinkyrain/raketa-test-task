<?php

declare(strict_types = 1);

namespace Raketa\BackendTestTask\Entity;

final readonly class Customer
{
    public function __construct(
        private string $uuid,
        private string $firstName,
        private string $lastName,
        private string $middleName,
        private string $email,
    ) {
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function getMiddleName(): string
    {
        return $this->middleName;
    }

    public function getEmail(): string
    {
        return $this->email;
    }
}

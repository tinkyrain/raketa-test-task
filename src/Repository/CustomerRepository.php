<?php

declare(strict_types = 1);

namespace Raketa\BackendTestTask\Repository;

use Doctrine\DBAL\Connection;
use Raketa\BackendTestTask\Entity\Customer;

class CustomerRepository
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function getCustomerBySessionId(string $sessionId): Customer|bool
    {
        $row = $this->connection->fetchAssociative(
            'SELECT * FROM customers WHERE email = ?',
            [$sessionId]
        );

        if (empty($row)) {  
            return false;
        }

        return $this->make($row);
    }

    public function make(array $row): Customer
    {
        return new Customer(
            $row['uuid'],
            $row['first_name'],
            $row['last_name'],
            $row['middle_name'],
            $row['email'],
        );
    }
}

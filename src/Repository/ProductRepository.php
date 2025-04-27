<?php

declare(strict_types = 1);

namespace Raketa\BackendTestTask\Repository;

use Doctrine\DBAL\Connection;
use Raketa\BackendTestTask\Entity\Product;

class ProductRepository
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function getByUuid(string $uuid): Product|bool
    {
        $row = $this->connection->fetchAssociative(
            'SELECT * FROM products WHERE uuid = ?',
            [$uuid]
        );

        if (empty($row)) {
            return false;
        }

        return $this->make($row);
    }

    /**
     * @param array<string> $uuids
     * @return array<Product>
     */
    public function getByUuids(array $uuids): array
    {
        if (empty($uuids)) {
            return [];
        }

        $placeholders = str_repeat('?,', count($uuids) - 1) . '?';
        $rows = $this->connection->fetchAllAssociative(
            "SELECT * FROM products WHERE uuid IN ($placeholders)",
            $uuids
        );

        return array_map(
            fn (array $row): Product => $this->make($row),
            $rows
        );
    }

    public function getByCategory(string $category): array
    {
        return array_map(
            fn (array $row): Product => $this->make($row),
            $this->connection->fetchAllAssociative(
                'SELECT * FROM products WHERE is_active = 1 AND category = ?',
                [$category]
            )
        );
    }

    public function make(array $row): Product
    {
        return new Product(
            $row['uuid'],
            $row['is_active'],
            $row['category'],
            $row['name'],
            $row['description'],
            $row['thumbnail'],
            $row['price'],
        );
    }
}

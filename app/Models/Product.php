<?php

/**
 * Product Model
 *
 * Encapsulates all database interactions for the `products` table.
 * Uses PDO prepared statements throughout to prevent SQL injection.
 */
class Product
{
    /** @var PDO */
    private PDO $db;

    /**
     * Accepts a PDO connection via constructor injection.
     *
     * @param PDO $db
     */
    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    /**
     * Retrieves every product row ordered by ID ascending.
     *
     * @return array<int, array<string, mixed>>
     */
    public function getAll(): array
    {
        $sql  = 'SELECT id, name, description, price, sku, image_url
                 FROM products
                 ORDER BY id ASC';

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    /**
     * Retrieves a single product by its primary key.
     *
     * @param int $id
     * @return array<string, mixed>|null  Returns null when no row is found.
     */
    public function getById(int $id): ?array
    {
        $sql = 'SELECT id, name, description, price, sku, image_url
                FROM products
                WHERE id = :id
                LIMIT 1';

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $row = $stmt->fetch();

        return $row !== false ? $row : null;
    }
}

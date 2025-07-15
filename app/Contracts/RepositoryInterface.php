<?php

namespace App\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Base Repository Interface
 * 
 * Defines the contract for all repository implementations
 */
interface RepositoryInterface
{
    /**
     * Get all records
     */
    public function all(): Collection;

    /**
     * Get paginated records
     */
    public function paginate(int $perPage = 15): LengthAwarePaginator;

    /**
     * Find a record by ID
     */
    public function find(int $id): ?Model;

    /**
     * Find a record by ID or fail
     */
    public function findOrFail(int $id): Model;

    /**
     * Find records by criteria
     */
    public function findBy(array $criteria): Collection;

    /**
     * Find first record by criteria
     */
    public function findOneBy(array $criteria): ?Model;

    /**
     * Create a new record
     */
    public function create(array $data): Model;

    /**
     * Update a record
     */
    public function update(int $id, array $data): Model;

    /**
     * Delete a record
     */
    public function delete(int $id): bool;

    /**
     * Get records with relationships
     */
    public function with(array $relations): self;

    /**
     * Apply where conditions
     */
    public function where(string $column, $operator = null, $value = null): self;

    /**
     * Apply order by
     */
    public function orderBy(string $column, string $direction = 'asc'): self;

    /**
     * Limit results
     */
    public function limit(int $limit): self;

    /**
     * Get count of records
     */
    public function count(): int;

    /**
     * Check if record exists
     */
    public function exists(array $criteria): bool;
}

<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * User Repository
 * 
 * Handles data access operations for User model
 */
class UserRepository extends BaseRepository
{
    /**
     * Get the model instance
     */
    protected function getModel(): Model
    {
        return new User();
    }

    /**
     * Get active users
     */
    public function getActiveUsers(): Collection
    {
        return $this->where('is_active', true)->all();
    }

    /**
     * Get users by role
     */
    public function getUsersByRole(string $role): Collection
    {
        return $this->model->role($role)->get();
    }

    /**
     * Get users for tenant
     */
    public function getUsersForTenant(int $tenantId): Collection
    {
        return $this->where('tenant_id', $tenantId)->all();
    }

    /**
     * Search users by name or email
     */
    public function searchUsers(string $search, int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->where(function ($query) use ($search) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
        })->paginate($perPage);
    }

    /**
     * Get users with roles
     */
    public function getUsersWithRoles(): Collection
    {
        return $this->with(['roles'])->all();
    }

    /**
     * Find user by email
     */
    public function findByEmail(string $email): ?User
    {
        return $this->model->where('email', $email)->first();
    }

    /**
     * Get recently logged in users
     */
    public function getRecentlyLoggedInUsers(int $days = 30): Collection
    {
        return $this->model->where('last_login_at', '>=', now()->subDays($days))
                          ->orderBy('last_login_at', 'desc')
                          ->get();
    }

    /**
     * Get users count by status
     */
    public function getUsersCountByStatus(): array
    {
        return [
            'active' => $this->model->where('is_active', true)->count(),
            'inactive' => $this->model->where('is_active', false)->count(),
            'total' => $this->model->count(),
        ];
    }

    /**
     * Get users with 2FA enabled
     */
    public function getUsersWithTwoFactor(): Collection
    {
        return $this->where('google2fa_enabled', true)->all();
    }

    /**
     * Update last login information
     */
    public function updateLastLogin(int $userId, string $ip): bool
    {
        return $this->model->where('id', $userId)->update([
            'last_login_at' => now(),
            'last_login_ip' => $ip,
        ]);
    }

    /**
     * Deactivate user
     */
    public function deactivateUser(int $userId): bool
    {
        return $this->update($userId, ['is_active' => false]);
    }

    /**
     * Activate user
     */
    public function activateUser(int $userId): bool
    {
        return $this->update($userId, ['is_active' => true]);
    }
}

<?php

namespace App\Services;

use App\Repositories\UserRepository;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\Permission\Models\Role;

/**
 * User Service
 * 
 * Handles business logic for user operations
 */
class UserService
{
    protected UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Get all users with pagination
     */
    public function getAllUsers(int $perPage = 15): LengthAwarePaginator
    {
        return $this->userRepository->with(['roles', 'tenant'])->paginate($perPage);
    }

    /**
     * Create a new user
     */
    public function createUser(array $data): User
    {
        return DB::transaction(function () use ($data) {
            // Hash password
            if (isset($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            }

            // Set tenant ID if in tenant context
            if (is_tenant_context() && !isset($data['tenant_id'])) {
                $data['tenant_id'] = tenant_id();
            }

            // Create user
            $user = $this->userRepository->create($data);

            // Assign role if provided
            if (isset($data['role'])) {
                $user->assignRole($data['role']);
            }

            // Log activity
            activity()
                ->causedBy(auth()->user())
                ->performedOn($user)
                ->log('User created');

            return $user;
        });
    }

    /**
     * Update user
     */
    public function updateUser(int $userId, array $data): User
    {
        return DB::transaction(function () use ($userId, $data) {
            $user = $this->userRepository->findOrFail($userId);

            // Hash password if provided
            if (isset($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            }

            // Update user
            $user = $this->userRepository->update($userId, $data);

            // Update role if provided
            if (isset($data['role'])) {
                $user->syncRoles([$data['role']]);
            }

            // Log activity
            activity()
                ->causedBy(auth()->user())
                ->performedOn($user)
                ->log('User updated');

            return $user;
        });
    }

    /**
     * Delete user
     */
    public function deleteUser(int $userId): bool
    {
        return DB::transaction(function () use ($userId) {
            $user = $this->userRepository->findOrFail($userId);

            // Log activity before deletion
            activity()
                ->causedBy(auth()->user())
                ->performedOn($user)
                ->log('User deleted');

            return $this->userRepository->delete($userId);
        });
    }

    /**
     * Search users
     */
    public function searchUsers(string $search, int $perPage = 15): LengthAwarePaginator
    {
        return $this->userRepository->searchUsers($search, $perPage);
    }

    /**
     * Get user by ID
     */
    public function getUserById(int $userId): User
    {
        return $this->userRepository->with(['roles', 'tenant'])->findOrFail($userId);
    }

    /**
     * Activate user
     */
    public function activateUser(int $userId): bool
    {
        $user = $this->userRepository->findOrFail($userId);
        
        $result = $this->userRepository->activateUser($userId);

        if ($result) {
            activity()
                ->causedBy(auth()->user())
                ->performedOn($user)
                ->log('User activated');
        }

        return $result;
    }

    /**
     * Deactivate user
     */
    public function deactivateUser(int $userId): bool
    {
        $user = $this->userRepository->findOrFail($userId);
        
        $result = $this->userRepository->deactivateUser($userId);

        if ($result) {
            activity()
                ->causedBy(auth()->user())
                ->performedOn($user)
                ->log('User deactivated');
        }

        return $result;
    }

    /**
     * Get users by role
     */
    public function getUsersByRole(string $role): Collection
    {
        return $this->userRepository->getUsersByRole($role);
    }

    /**
     * Get user statistics
     */
    public function getUserStatistics(): array
    {
        $stats = $this->userRepository->getUsersCountByStatus();
        
        $stats['with_2fa'] = $this->userRepository->getUsersWithTwoFactor()->count();
        $stats['recent_logins'] = $this->userRepository->getRecentlyLoggedInUsers(7)->count();

        return $stats;
    }

    /**
     * Assign role to user
     */
    public function assignRole(int $userId, string $roleName): bool
    {
        $user = $this->userRepository->findOrFail($userId);
        $role = Role::findByName($roleName);

        if (!$role) {
            throw new \Exception("Role '{$roleName}' not found");
        }

        $user->assignRole($role);

        activity()
            ->causedBy(auth()->user())
            ->performedOn($user)
            ->withProperties(['role' => $roleName])
            ->log('Role assigned to user');

        return true;
    }

    /**
     * Remove role from user
     */
    public function removeRole(int $userId, string $roleName): bool
    {
        $user = $this->userRepository->findOrFail($userId);
        $user->removeRole($roleName);

        activity()
            ->causedBy(auth()->user())
            ->performedOn($user)
            ->withProperties(['role' => $roleName])
            ->log('Role removed from user');

        return true;
    }

    /**
     * Enable 2FA for user
     */
    public function enable2FA(int $userId, string $secret): bool
    {
        $user = $this->userRepository->findOrFail($userId);
        
        $result = $this->userRepository->update($userId, [
            'google2fa_secret' => $secret,
            'google2fa_enabled' => true,
        ]);

        if ($result) {
            activity()
                ->causedBy(auth()->user())
                ->performedOn($user)
                ->log('2FA enabled for user');
        }

        return (bool) $result;
    }

    /**
     * Disable 2FA for user
     */
    public function disable2FA(int $userId): bool
    {
        $user = $this->userRepository->findOrFail($userId);
        
        $result = $this->userRepository->update($userId, [
            'google2fa_secret' => null,
            'google2fa_enabled' => false,
        ]);

        if ($result) {
            activity()
                ->causedBy(auth()->user())
                ->performedOn($user)
                ->log('2FA disabled for user');
        }

        return (bool) $result;
    }
}

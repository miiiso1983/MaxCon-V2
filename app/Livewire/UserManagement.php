<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Services\UserService;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

/**
 * User Management Livewire Component
 *
 * Handles CRUD operations for users with real-time updates
 */
class UserManagement extends Component
{
    use WithPagination;

    protected UserService $userService;

    // Component properties
    public $search = '';
    public $selectedRole = '';
    public $showModal = false;
    public $editMode = false;
    public $userId = null;

    // Form properties
    public $name = '';
    public $email = '';
    public $password = '';
    public $password_confirmation = '';
    public $role = '';
    public $is_active = true;

    // Validation rules
    protected function rules()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'role' => 'required|exists:roles,name',
            'is_active' => 'boolean',
        ];

        if (!$this->editMode) {
            $rules['email'] .= '|unique:users,email';
            $rules['password'] = 'required|string|min:8|confirmed';
        } else {
            $rules['email'] .= '|unique:users,email,' . $this->userId;
            $rules['password'] = 'nullable|string|min:8|confirmed';
        }

        return $rules;
    }

    public function boot(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function mount()
    {
        // Check permissions
        if (!auth()->user()->can('users.view')) {
            abort(403);
        }
    }

    public function render()
    {
        $users = $this->getUsers();
        $roles = Role::all();

        return view('livewire.user-management', [
            'users' => $users,
            'roles' => $roles,
        ]);
    }

    private function getUsers()
    {
        $query = User::with(['roles', 'tenant']);

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->selectedRole) {
            $query->role($this->selectedRole);
        }

        return $query->paginate(10);
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedSelectedRole()
    {
        $this->resetPage();
    }

    public function openCreateModal()
    {
        if (!auth()->user()->can('users.create')) {
            session()->flash('error', 'You do not have permission to create users.');
            return;
        }

        $this->resetForm();
        $this->editMode = false;
        $this->showModal = true;
    }

    public function openEditModal($userId)
    {
        if (!auth()->user()->can('users.edit')) {
            session()->flash('error', 'You do not have permission to edit users.');
            return;
        }

        $user = User::findOrFail($userId);

        $this->userId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->role = $user->roles->first()?->name ?? '';
        $this->is_active = $user->is_active;

        $this->editMode = true;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        try {
            $data = [
                'name' => $this->name,
                'email' => $this->email,
                'role' => $this->role,
                'is_active' => $this->is_active,
            ];

            if ($this->password) {
                $data['password'] = $this->password;
            }

            if ($this->editMode) {
                $this->userService->updateUser($this->userId, $data);
                session()->flash('success', 'User updated successfully.');
            } else {
                $this->userService->createUser($data);
                session()->flash('success', 'User created successfully.');
            }

            $this->closeModal();
        } catch (\Exception $e) {
            session()->flash('error', 'Operation failed: ' . $e->getMessage());
        }
    }

    public function deleteUser($userId)
    {
        if (!auth()->user()->can('users.delete')) {
            session()->flash('error', 'You do not have permission to delete users.');
            return;
        }

        try {
            $this->userService->deleteUser($userId);
            session()->flash('success', 'User deleted successfully.');
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to delete user: ' . $e->getMessage());
        }
    }

    public function toggleUserStatus($userId)
    {
        if (!auth()->user()->can('users.edit')) {
            session()->flash('error', 'You do not have permission to modify users.');
            return;
        }

        try {
            $user = User::findOrFail($userId);

            if ($user->is_active) {
                $this->userService->deactivateUser($userId);
                session()->flash('success', 'User deactivated successfully.');
            } else {
                $this->userService->activateUser($userId);
                session()->flash('success', 'User activated successfully.');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to update user status: ' . $e->getMessage());
        }
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    private function resetForm()
    {
        $this->userId = null;
        $this->name = '';
        $this->email = '';
        $this->password = '';
        $this->password_confirmation = '';
        $this->role = '';
        $this->is_active = true;
        $this->resetErrorBag();
    }
}

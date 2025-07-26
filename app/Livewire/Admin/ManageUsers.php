<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use App\Livewire\Forms\admin\UserForm;
use Livewire\Attributes\Title;

class ManageUsers extends Component
{
    use WithPagination;

    public UserForm $form;

    public User $user;

    public int $perPage = 10; // Default per page

    public string $search = '';

    public string $sortBy = 'latest_activity';

    public array $selectedUsers = [];

    #[Title('Daftar User')]
    public function render()
    {
        $users = $this->loadUsers();
        $users = $this->filterUsers($users);
        return view('livewire.admin.manage-users', [
            'users' => $users->paginate($this->perPage),
        ]);
    }

    public function loadUsers()
    {
        $query = User::with('roles')->whereDoesntHave('roles', function ($query) {
            $query->where('name', 'administrator');
        });

        if ($this->sortBy === 'latest_activity') {
            $query->latest('updated_at');
        } else {
            $query->latest('created_at');
        }
        return $query;
    }

    public function sortBy($field)
    {
        $this->sortBy = $field;
        $this->resetPage();
    }

    public function filterUsers($users)
    {
        if (empty($this->search)) {
            return $users;
        }

        $searchValue = strtolower($this->search);

        $searchTerm = '%' . $searchValue . '%';

        return $users->where(function ($query) use ($searchTerm) {
            $query->where('name', 'like', $searchTerm)
                ->orWhere('email', 'like', $searchTerm)
                ->orWhere('section', 'like', $searchTerm);
        });
    }

    // public function createUser()
    // {
    //     $this->authorize('create user');

    //     $this->form->store();

    //     $this->reset('form');

    //     return redirect()->route('manage.users');
    // }

    public function deleteUsers()
    {
        $this->authorize('delete user');

        $this->form->delete($this->selectedUsers);

        $this->reset('selectedUsers');

        return $this->redirectRoute('manage.users', navigate: true);
    }
}

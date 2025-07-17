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

    public string $password = '';

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
        $query = User::with('roles');

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

        $searchTerm = '%' . $this->search . '%';

        return $users->where(function ($query) use ($searchTerm) {
            $query->where('name', 'like', $searchTerm)
                ->orWhere('email', 'like', $searchTerm)
                ->orWhere('section', 'like', $searchTerm);
        });
    }

    public function show(int $id)
    {
        $this->redirectRoute('user.show', ['id' => $id]);
    }

    public function createPage()
    {
        $this->dispatch('create-user');
    }

    public function updatePage($id)
    {
        $this->dispatch('update-user', $id);
    }

    public function deleteUsers()
    {
        $this->validate([
            'password' => ['required', 'string', 'current_password'],
        ]);

        User::whereIn('id', $this->selectedUsers)->delete();

        $this->reset();

        return redirect()->route('manage.users');
    }
}

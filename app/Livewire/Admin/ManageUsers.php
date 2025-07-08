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

    public $perPage = 10; // Default per page

    public $selectedUsers = [];

    #[Title('Daftar User')]
    public function render()
    {
        return view('livewire.admin.manage-users', [
            'users' => $this->loadUsers(),
        ]);
    }

    public function loadUsers()
    {
        return User::with('roles')
            ->paginate($this->perPage);
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

        $this->selectedUsers = [];

        return redirect()->route('manage.users');
    }
}

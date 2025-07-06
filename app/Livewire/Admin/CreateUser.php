<?php

namespace App\Livewire\Admin;

use App\Livewire\Forms\admin\UserForm;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Computed;
use App\Models\User;

class CreateUser extends Component
{
    public UserForm $form;

    public function render()
    {
        return view('livewire.admin.create-user');
    }

    #[Computed]
    public function getSections()
    {
        return User::getSections();
    }

    #[On('create-user')]
    public function open()
    {
        $this->form->reset();
    }

    public function save()
    {
        $this->form->store();

        return redirect()->route('manage.users');
    }
}

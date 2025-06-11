<?php

namespace App\Livewire\Admin;

use App\Livewire\Forms\admin\UserForm;
use Livewire\Component;
use Livewire\Attributes\On;

class CreateUser extends Component
{

    public UserForm $form;

    public function render()
    {
        return view('livewire.admin.create-user');
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

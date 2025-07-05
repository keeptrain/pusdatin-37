<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\On;
use App\Livewire\Forms\admin\UserForm;

class UpdateUser extends Component
{
    public UserForm $form;

    public $id = null;

    public $showUpdateModal = false;

    public function placeholder()
    {
        return <<<'HTML'
        <div>
            <flux:icon.loading />
        </div>
        HTML;
    }

    #[On('update-user')]
    public function open($id)
    {
        $user = User::findOrFail($id);
        $this->showUpdateModal = true;
        $this->form->setUser($user);
    }

    public function save()
    {
        $this->form->update();
    }

    public function render()
    {
        return view('livewire.admin.update-user');
    }
}

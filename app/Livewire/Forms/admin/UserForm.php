<?php

namespace App\Livewire\Forms\admin;

use Livewire\Form;
use App\Models\User;
use Illuminate\Validation\Rule;

class UserForm extends Form
{
    public ?User $user;

    public $id = null;

    public $name = '';

    public $email = '';

    public $section = '';

    public $contact = '';

    public $password = '';

    public $role = 'user';

    public function rules()
    {
        // dd("Current user ID being validated: " . $this->id);
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                $this->id ? Rule::unique('users', 'email')->ignore($this->id) : 'unique:users,email'
            ],
            'section' => ['required', 'string', 'max:255'],
            'contact' => ['required', 'string', 'max:255'],
            'password' => $this->id
                ? ['nullable']
                : ['required', 'string', 'min:8', 'max:46'],
            'role' => [
                'required',
                'string',
                Rule::exists('roles', 'name')
            ],
        ];
    }

    public function setUser(User $user)
    {

        $this->id = $user->id;

        $this->name = $user->name;

        $this->email = $user->email;

        $this->section = $user->section;

        $this->contact = $user->contact;

        $this->role = $user->roles->first()->name ?? null;

    }

    public function store()
    {
        $this->validate();

        $this->user = User::create(
            $this->all()
        );

        $this->user->assignRole($this->role);

        $this->reset();
    }

    public function update()
    {
        $this->validate();

        $this->user = User::findOrFail($this->id);

        $this->user->update(
            $this->only(['name', 'email', 'role'])
        );

        $this->user->syncRoles($this->role);

        return redirect()->route('manage.users');

    }

}

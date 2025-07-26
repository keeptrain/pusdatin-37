<?php

namespace App\Livewire\Forms\admin;

use App\Mail\AccountCreatedMail;
use Livewire\Form;
use App\Models\User;
use Illuminate\Validation\Rule;
use Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class UserForm extends Form
{
    public ?User $user;

    public ?int $id = null;

    public string $name = '';

    public string $email = '';

    public string $section = '';

    public string $contact = '';

    public string $role = 'user';

    public string $password = '';

    public function rules()
    {
        // dd("Current user ID being validated: " . $this->id);
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                $this->id ? Rule::unique('users', 'email')->ignore($this->id) : 'unique:users,email'
            ],
            'section' => ['required', 'string', 'max:100'],
            'contact' => ['required', 'string', 'max:16'],
            // 'password' => $this->id
            //     ? ['nullable']
            //     : ['required', 'string', 'min:8', 'max:46'],
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

        try {
            DB::transaction(function () {
                $password = Str::random(10);

                $user = User::create(
                    [
                        'name' => $this->name,
                        'email' => $this->email,
                        'password' => bcrypt($password),
                        'section' => $this->section,
                        'contact' => $this->contact,
                    ]
                );

                $user->assignRole($this->role);

                DB::afterCommit(function () use ($user, $password) {
                    $data = [
                        'name' => $user->name,
                        'email' => $user->email,
                        'password' => $password,
                    ];
                    Mail::to($user->email)->send(new AccountCreatedMail($data));
                });
            });
        } catch (\Exception $e) {
            throw $e;
        }

        $this->reset();
    }

    public function delete(array $selectedUsers)
    {
        $this->validate([
            'password' => ['required', 'string', 'current_password'],
        ]);

        DB::transaction(function () use ($selectedUsers) {
            User::whereIn('id', $selectedUsers)->whereDoesntHave('roles', function ($query) {
                $query->where('name', 'administrator');
            })->delete();
        });

        $this->reset();
    }
}
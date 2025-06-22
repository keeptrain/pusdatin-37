<?php

namespace App\Livewire\Admin\User;

use App\Models\User;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Validation\Rule;

class Show extends Component
{

    #[Locked]
    public $userId;

    public $user;

    public $name;

    public $email;

    public $section;

    public $contact;

    public $role;

    #[Title('Detail User')]
    public function render()
    {
        return view('livewire.admin.user.show');
    }

    public function mount($id)
    {
        $this->userId = $id;
        $this->user = $this->user();
        $this->name = $this->user->name;
        $this->email = $this->user->email;
        $this->section = $this->user->section;
        $this->contact = $this->user->contact;
        $this->role = $this->user->roles->first()->name;
    }

    public function user()
    {
        return User::findOrFail($this->userId);
    }




    #[Computed]
    public function requestsOfUser()
    {
        $user = $this->user;

        $informationSystemRequests = $user->informationSystemRequests()->get(['id', 'title', 'reference_number', 'created_at']);
        $publicRelationRequests = $user->publicRelationRequests()->get(['id', 'theme', 'target', 'created_at']);

        return [
            'informationSystemRequests' => $informationSystemRequests,
            'publicRelationRequests' => $publicRelationRequests
        ];
    }

    public function update()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $this->userId,
            'section' => ['required', Rule::in(array_keys($this->user->sections))],
            'contact' => 'required|string|max:255',
            'role' => 'required|string|exists:roles,name',
        ]);

        DB::transaction(function () {
            $this->user->update([
                'name' => $this->name,
                'email' => $this->email,
                'section' => $this->section,
                'contact' => $this->contact
            ]);

            $this->user->syncRoles($this->role);
        });

        $this->redirectRoute('user.show', $this->userId);
    }

    #[Computed]
    public function getRolesNames()
    {
        // $roles = Role::pluck('id', 'id')->toArray();
        // $readableNames = [];

        // foreach ($roles as $roleId) {
        //     $division = Division::tryFrom($roleId);

        //     if ($division) {
        //         $readableNames[$roleId] = $division->label();
        //     } else {
        //         $readableNames[$roleId] = 'User';
        //     }
        // }

        // return $readableNames;

        return Role::pluck('name', 'id')->toArray();
    }

    #[Computed]
    public function getSections()
    {
        return $this->user->sections;
    }
}

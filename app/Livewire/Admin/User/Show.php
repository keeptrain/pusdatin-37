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
    public int $userId;

    public User $user;

    public string $name;

    public string $email;

    public string $section;

    public string $contact;

    public string $role;

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

    #[Computed]
    public function requestCount()
    {
        $user = $this->user;

        $informationSystemCount = $user->informationSystemRequests()->count();
        $publicRelationCount = $user->publicRelationRequests()->count();

        return $informationSystemCount + $publicRelationCount;
    }

    #[Computed]
    public function discussionCount()
    {
        return $this->user->discussions()->count();
    }

    public function update()
    {
        $this->authorize('update user');

        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $this->userId,
            'section' => ['required', Rule::in(array_keys($this->user->sections))],
            'contact' => 'required|string|max:255',
            'role' => [
                'required',
                'string',
                'exists:roles,name',
            ],
        ]);

        DB::transaction(function () {
            // Update user details
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

    public function deleteUser()
    {
        $this->authorize('delete user');

        $this->validate([
            'password' => ['required', 'string', 'current_password'],
        ]);

        $this->user->delete();

        $this->redirectRoute('manage.users', navigate: true);
    }


    #[Computed]
    public function getSections()
    {
        return User::getSections();
    }
}

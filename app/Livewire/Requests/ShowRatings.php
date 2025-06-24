<?php

namespace App\Livewire\Requests;

use Livewire\Component;
use Livewire\Attributes\Title;
use App\Models\InformationSystemRequest;
use App\Models\PublicRelationRequest;
use Livewire\WithPagination;

#[Title('Penilaian Layanan')]
class ShowRatings extends Component
{
    use WithPagination;

    public $contentType;

    public function mount()
    {
    }

    public function render()
    {
        return view('livewire.requests.show-ratings', [
            'contents' => $this->loadContent()->simplePaginate(10)
        ]);
    }

    protected function loadContent()
    {
        $roleId = auth()->user()->currentUserRoleId();

        return match ($roleId) {
            3, 4 => $this->systemRequests($roleId),
            5 => $this->prRequests(),
            default => abort(404, 'Invalid content type.')
        };
    }

    public function systemRequests(int $division)
    {
        return InformationSystemRequest::select('id', 'user_id','current_division','title', 'rating')->with('user:id,name,section')->where('current_division', $division)->orderBy('updated_at', 'desc');
    }

    public function prRequests()
    {
        return PublicRelationRequest::select('id', 'user_id', 'theme', 'rating')->with('user:id,name,section')->orderBy('updated_at', 'desc');
    }
}

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
        $roleId = auth()->user()->roles->pluck('id')->first();

        return match ($roleId) {
            3, 4 => $this->systemRequests(),
            5 => $this->prRequests(),
            default => abort(404, 'Invalid content type.')
        };
    }

    public function systemRequests()
    {
        return InformationSystemRequest::select('id', 'user_id', 'title', 'rating')->with('user:id,name,section')->orderBy('updated_at', 'desc');
    }

    public function prRequests()
    {
        return PublicRelationRequest::select('id', 'user_id', 'theme', 'rating')->with('user:id,name,section')->orderBy('updated_at', 'desc');
    }
}

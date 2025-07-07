<?php

namespace App\Livewire\Discussions;

use App\Livewire\Forms\DiscussionForm;
use Livewire\Attributes\Locked;
use Livewire\Component;
use App\Models\Discussion;
use Illuminate\Support\Facades\DB;

class Show extends Component
{
    #[Locked]
    public int $discussionId;
    // public $discussion;

    public DiscussionForm $form;

    public function render()
    {
        $discussion = $this->discussion();
        $replies = $discussion->replies;
        return view('livewire.discussions.show', compact('discussion', 'replies'));
    }

    public function mount(int $id)
    {
        $this->discussionId = $id;
    }

    public function discussion()
    {
        return Discussion::with('replies')->findOrFail($this->discussionId);
    }

    public function reply()
    {
        try {
            $this->form->storeReply($this->discussionId);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function deleteReply(int $replyId)
    {
        try {
            DB::transaction(function () use ($replyId) {
                Discussion::findOrFail($replyId)->delete();
            });
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}

<?php

namespace App\Livewire\Discussions;

use App\Livewire\Forms\DiscussionForm;
use Livewire\Attributes\Locked;
use Livewire\Component;
use App\Models\Discussion;
use Illuminate\Support\Facades\DB;
use Livewire\WithFileUploads;


class Show extends Component
{
    use WithFileUploads;

    #[Locked]
    public int $discussionId;

    public string $routeBack = '';

    public DiscussionForm $form;

    public string $status = 'Selesaikan diskusi';

    public array $imagesUpload = [];

    public function render()
    {
        $discussion = $this->discussion();
        $this->currentStatus($discussion);
        $replies = $discussion->replies;
        $attachments = $replies->load('attachments');
        $firstAttachments = $discussion->attachments;
        return view('livewire.discussions.show', compact('discussion', 'replies', 'attachments', 'firstAttachments'));
    }

    public function mount(int $id)
    {
        $this->discussionId = $id;
        $this->routeBack = auth()->user()->hasRole('user') ? 'dashboard' : 'discussions';
    }

    public function discussion()
    {
        return Discussion::with('replies', 'attachments')->findOrFail($this->discussionId);
    }

    public function updatedImagesUpload($value)
    {
        foreach ($this->imagesUpload as $image) {
            $this->form->attachments[] = $image;
        }
    }

    public function removeTemporaryImage(int $index)
    {
        unset($this->form->attachments[$index]);
        $this->form->attachments = array_values($this->form->attachments);
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
            $this->form->deleteReply($replyId);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function deleteDiscussion()
    {
        try {
            $this->form->deleteDiscussion($this->discussionId);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function currentStatus($discussion)
    {
        $discussion->closed_at ? $this->status = 'Buka diskusi' : $this->status = 'Selesaikan diskusi';
    }

    public function statusDiscussion()
    {
        try {
            DB::transaction(function () {
                $discussion = Discussion::findOrFail($this->discussionId);
                $discussion->closed_at ? $discussion->reopen() : $discussion->close();

                DB::afterCommit(function () use ($discussion) {
                    $this->currentStatus($discussion);
                });
            });
            // session()->flash('status', [
            //     'variant' => 'success',
            //     'message' => 'Diskusi berhasil di selesaikan',
            // ]);
            $this->redirectRoute('discussion.show', $this->discussionId, navigate: true);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}

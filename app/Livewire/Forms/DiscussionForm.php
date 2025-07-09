<?php

namespace App\Livewire\Forms;

use App\Enums\Division;
use Livewire\Attributes\Validate;
use Livewire\Form;
use App\Models\Discussion;
use App\Models\InformationSystemRequest;
use App\Models\PublicRelationRequest;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;

class DiscussionForm extends Form
{
    #[Validate('required|string|in:yes,no')]
    public $discussableType = '';

    #[Validate('required_if:discussableType,yes')]
    public $discussableId = '';

    #[Validate('required_if:discussableType,no|string|in:si,data,pr')]
    public $kasatpel = '';

    #[Validate(['attachments.*' => 'image|max:1024'])]
    public $attachments = [];

    #[Validate('required|string')]
    public $body = '';

    public $replyStates = [];

    public function attachments(Discussion $discussion, ?int $discussionId)
    {
        // if (isset($this->replyStates[$discussionId]['attachments'])) {
        //     foreach ($this->replyStates[$discussionId]['attachments'] as $image) {
        //         $path = $image->store('attachments/discussions');

        //         $discussion->attachments()->create([
        //             'user_id' => auth()->user()->id,
        //             'disk' => 'public',
        //             'path' => $path,
        //             'original_filename' => $image->getClientOriginalName(),
        //             'mime_type' => $image->getMimeType(),
        //         ]);
        //     }
        // };

        if (isset($this->attachments)) {
            foreach ($this->attachments as $image) {
                $path = $image->store('attachments/discussions');
                $discussion->attachments()->create(attributes: [
                    'user_id' => auth()->user()->id,
                    'disk' => 'public',
                    'path' => $path,
                    'original_filename' => $image->getClientOriginalName(),
                    'mime_type' => $image->getMimeType(),
                ]);
            }
        }
        ;
    }

    public function removeAttachments($index)
    {
        unset($this->attachments[$index]);
        $this->attachments = array_values($this->attachments);
    }

    public function store()
    {
        $this->validate();

        DB::transaction(function () {
            $discussable = $this->resolveDiscussable();

            $discussion = Discussion::create([
                'user_id' => auth()->user()->id,
                'body' => $this->body,
                'discussable_type' => $discussable[0],
                'discussable_id' => $discussable[1],
            ]);

            $this->attachments($discussion, null);

            DB::afterCommit(function () {
                $this->reset();
            });
        });
    }

    protected function resolveDiscussable(): array
    {
        if ($this->discussableType === 'no') {
            return [
                Role::class,
                Division::getIdFromString($this->kasatpel)
            ];
        }

        [$type, $id] = explode(':', $this->discussableId);

        return match ($type) {
            'Sistem Informasi dan Data' => [InformationSystemRequest::class, $id],
            'Kehumasan' => [PublicRelationRequest::class, $id],
            default => throw new \Exception('Invalid request type')
        };
    }

    public function storeReply(int $discussionId)
    {
        DB::transaction(function () use ($discussionId) {
            $discussion = Discussion::findOrFail($discussionId);
            $discussion = Discussion::create([
                'user_id' => auth()->user()->id,
                'body' => $this->replyStates[$discussionId]['body'],
                'discussable_type' => $discussion->discussable_type,
                'discussable_id' => $discussion->discussable_id,
                'parent_id' => $discussionId,
            ]);

            $this->attachments($discussion, $discussionId);

            DB::afterCommit(function () use ($discussion) {
                $this->reset();
            });
        });
    }
}

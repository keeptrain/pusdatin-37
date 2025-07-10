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
use Illuminate\Support\Facades\Storage;

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

    public function messages()
    {
        return [
            'discussableType.required' => 'Harap pilih jenis diskusi.',
            'discussableType.in' => 'Jenis diskusi tidak valid.',
            'discussableId.required_if' => 'Harap pilih diskusi terkait.',
            'discussableId.string' => 'Diskusi terkait harus berupa teks.',
            'kasatpel.required_if' => 'Harap pilih kasatpel.',
            'kasatpel.in' => 'Kasatpel tidak valid.',
            'attachments.*.image' => 'File harus berupa gambar.',
            'attachments.*.max' => 'Ukuran gambar tidak boleh lebih dari 1MB.',
            'body.required' => 'Harap masukkan pesan diskusi.',
            'body.string' => 'Pesan diskusi harus berupa teks.'
        ];
    }

    public function attachments(Discussion $discussion, ?int $discussionId)
    {
        if (isset($this->attachments)) {
            foreach ($this->attachments as $image) {
                $path = $image->store('attachments/discussions');
                $discussion->attachments()->create(attributes: [
                    'user_id' => auth()->user()->id,
                    'disk' => 'local',
                    'path' => $path,
                    'original_filename' => $image->getClientOriginalName(),
                    'mime_type' => $image->getMimeType(),
                ]);
            }
        }
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

    public function storeReply(int $parentId)
    {
        DB::transaction(function () use (&$parentId) {
            $discussionParent = Discussion::findOrFail($parentId);
            $reply = Discussion::create([
                'user_id' => auth()->user()->id,
                'body' => $this->replyStates[$parentId]['body'],
                'discussable_type' => $discussionParent->discussable_type,
                'discussable_id' => $discussionParent->discussable_id,
                'parent_id' => $parentId,
            ]);

            $this->attachments($reply, $parentId);

            DB::afterCommit(function () use ($reply) {
                $this->reset();
            });
        });
    }

    public function deleteReply(int $replyId)
    {
        DB::transaction(function () use (&$replyId) {
            $reply = Discussion::findOrFail($replyId);

            if ($reply->user_id == auth()->user()->id) {
                Storage::delete($reply->attachments->pluck('path'));
                $reply->delete();
            }
        });
    }

    public function deleteDiscussion(int $discussionId)
    {
        DB::transaction(function () use (&$discussionId) {
            $discussion = Discussion::findOrFail($discussionId);

            if ($discussion->user_id == auth()->user()->id) {
                $discussion->delete();
            }
        });
    }
}

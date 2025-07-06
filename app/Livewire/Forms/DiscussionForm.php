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

    #[Validate('required|string')]
    public $body = '';

    public $replyStates = [];

    public function store()
    {
        $this->validate();

        DB::transaction(function () {
            $discussable = $this->resolveDiscussable();

            Discussion::create([
                'user_id' => auth()->user()->id,
                'body' => $this->body,
                'discussable_type' => $discussable[0],
                'discussable_id' => $discussable[1],
            ]);

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
        // $this->validateOnly("replyStates.$discussionId.body", 'required|string|max:1000', $discussionId);
        DB::transaction(function () use ($discussionId) {
            $discussion = Discussion::findOrFail($discussionId);
            Discussion::create([
                'user_id' => auth()->user()->id,
                'body' => $this->replyStates[$discussionId]['body'],
                'discussable_type' => $discussion->discussable_type,
                'discussable_id' => $discussion->discussable_id,
                'parent_id' => $discussionId,
            ]);
            DB::afterCommit(function () use ($discussion) {
                $this->reset('replyStates');
            });
        });
    }
}

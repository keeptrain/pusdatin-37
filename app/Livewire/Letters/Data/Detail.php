<?php

namespace App\Livewire\Letters\Data;

use LogicException;
use App\States\Process;
use Livewire\Component;
use App\Models\Letters\Letter;
use Illuminate\Support\Facades\DB;
use App\Models\Letters\LetterDirect;
use App\Models\Letters\LetterUpload;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Detail extends Component
{
    public ?Letter $letter;

    public int $letterId;

    public $letterUpload = null;

    public $letterDirect = null;

    public function mount(int $id)
    {
        $this->letterId = $id;

        $this->getLetter();
    }

    public function getLetter()
    {
        return once(
            fn() =>
            $this->letter = Letter::with('letterable')->findOrFail($this->letterId)
        );
    }

    public function processLetter(int $id)
    {
        if (Auth::user()->withoutRole('user')) {
            DB::transaction(function () {
                $this->letter->status->transitionTo(Process::class);
                $this->letter->requestStatusTrack()->create([
                    'letter_id' => $this->letterId,
                    'action' => "Permohonan layanan sedang di proses , harap cek berkala."
                ]);
            });

            return redirect()->route('letter.table')->with([
                'status' => [
                    'variant' => 'success',
                    'message' => 'Letter has update to read status!'
                ]
            ]);
        } else {
            return redirect()->route('letter.detail', [$id])->with([
                'status' => [
                    'variant' => 'error',
                    'message' => 'Letter has update to read status!'
                ]
            ]);
        }
    }

    public function repliedLetter($letterId)
    {
        $this->dispatch('modal-confirmation', $letterId);
    }

    public function backStatus()
    {
        $this->letter->status->transitionTo(Process::class);
    }

    private function loadLetterData(int $id)
    {
        try {
            $this->letter = Letter::with('letterable')->findOrFail($id);
        } catch (ModelNotFoundException $e) {
            throw new NotFoundHttpException('Letter record not found.');
        }
    }

    private function processLetterUpload(LetterUpload $letterable): void
    {
        $this->letterUpload = $letterable->file_path;
    }

    private function processLetterDirect(LetterDirect $letterable): void
    {
        $this->letterDirect = $letterable->body;
    }

    private function handleInvalidLetterable(?object $letterable, int $letterId): void
    {
        if (is_null($letterable)) {
            throw new LogicException('Associated letterable record is missing or invalid for Letter ID: ' . $letterId);
        } else {
            $type = get_class($letterable);
            throw new LogicException("Unsupported letterable type '{$type}' for Letter ID: " . $letterId);
        }
    }
}

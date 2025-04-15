<?php

namespace App\Livewire\Letters\Data;

use LogicException;
use Livewire\Component;
use App\Models\Letters\Letter;
use App\Models\Letters\LetterDirect;
use App\Models\Letters\LetterUpload;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Detail extends Component
{
    public $letter;

    public $letterUpload = null;

    public $letterDirect = null;

    public function mount(int $id)
    {
        $this->loadLetterData($id);

        $letterable = $this->letter->letterable;

        if ($letterable instanceof LetterUpload) {
            $this->processLetterUpload($letterable);
        } elseif ($letterable instanceof LetterDirect) {
            $this->processLetterDirect($letterable);
        } else {
            $this->handleInvalidLetterable($letterable, $id);
        }

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

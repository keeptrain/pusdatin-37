<?php

namespace App\Livewire\Requests\InformationSystem;

use App\Livewire\Forms\modal\InformationSystem\ActionForm;
use Livewire\Component;
use Livewire\Attributes\Locked;

class ConfirmModal extends Component
{
    #[Locked]
    public int $systemRequestId;

    public ActionForm $form;

    public $allowedParts;

    public function mount(int $systemRequestId, $allowedParts)
    {
        $this->systemRequestId = $systemRequestId;
        $this->allowedParts = $allowedParts;
    }

    public function actionDisposition()
    {
        $this->authorize('can disposition', $this->systemRequestId);

        $this->form->disposition($this->systemRequestId);

        $this->redirectRoute('is.show', $this->systemRequestId, navigate: true);
    }

    public function actionVerification()
    {
        // need authorize
        // $this->authorize('canPerformStep1Verification', $this->getInformationSystemRequest());

        $this->form->verification($this->systemRequestId);

        $this->redirectRoute('is.show', $this->systemRequestId, navigate: true);
    }


    public function processPusdatin()
    {
        try {
            $this->form->process($this->systemRequestId);
        } catch (\Exception $e) {
            $this->form->addError('error', $e->getMessage());
            return;
        }

        $this->redirectRoute('is.show', $this->systemRequestId, navigate: true);
    }

    public function completed()
    {
        $result = $this->form->completed($this->systemRequestId);

        // Redirect if completed successfully
        if ($result) {
            $this->redirectRoute('is.show', $this->systemRequestId, navigate: true);
        }
    }
}

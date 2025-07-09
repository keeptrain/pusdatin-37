<?php

namespace App\Livewire;

use App\Livewire\Discussions\Index;
use App\Livewire\Forms\DiscussionForm;
use Livewire\Attributes\Modelable;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

class ImageUploader extends Component
{
    use WithFileUploads;

    // #[Modelable]
    // public $value;

    public ?int $discussionId;

    public $form;

    // #[Validate('image|max:1048')]
    // public $imagesUpload = [];

    public function mount()
    {
    }

    // public function updatedValue($value)
    // {
    //     foreach ($this->value as $item)
    //     {
    //         $item->store('livewire-tmp');
    //     }

    // }

    // public function updatedImagesUpload()
    // {
    //     // $this->validate();
    //     $temporaryUrl = [];
    //     foreach ($this->imagesUpload as $image) {
    //         // Store each image to livewire temporary
    //         $path = $image->store('livewire-tmp');
    //         $temporaryUrl[] = [
    //             'temp-path' => $path,
    //             'original_filename' => $image->getClientOriginalName(),
    //             'mim'
    //         ];
    //     }

    //     $this->dispatch('upload-images', path: $temporaryUrl);
    // }

    // public function removeTemporaryImage($index)
    // {
    //     if (isset($this->imagesUpload[$index])) {
    //         $this->dispatch('removeUploaded', index: $index);
    //         unset($this->imagesUpload[$index]);
    //         $this->imagesUpload = array_values($this->imagesUpload);

    //     }
    // }

    public function render()
    {
        return view('livewire.image-uploader');
    }
}
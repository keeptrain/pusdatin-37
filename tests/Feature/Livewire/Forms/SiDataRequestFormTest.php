<?php

namespace Tests\Feature\Livewire\Forms;

use Tests\TestCase;
use Livewire\Livewire;
use App\Livewire\Forms\SiDataRequestForm;

class SiDataRequestFormTest extends TestCase
{
    public function render_successfully()
    {
        return Livewire::test(SiDataRequestForm::class)
            ->assertStatus(200);
    }

}
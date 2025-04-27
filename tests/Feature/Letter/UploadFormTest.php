<?php

use App\Models\User;
use Livewire\Livewire;
use App\Models\Letters\Letter;
use Illuminate\Http\UploadedFile;
use App\Livewire\Letters\UploadForm;
use App\Models\Letters\LetterUpload;
use App\Livewire\Letters\CreateLetter;
use Illuminate\Support\Facades\Storage;
use App\Models\Letters\RequestStatusTrack;


test('LetterUpload page expected', function () {
    Livewire::test(UploadForm::class)
        ->assertStatus(200);
});

test('form validation fails with invalid input', function () {
    Livewire::test(UploadForm::class)
        ->set('title', '')
        ->set('responsible_person', '')
        ->set('reference_number', '')
        ->set('files', '')
        ->call('save')
        ->assertHasErrors([
            'title' => 'required',
            'responsible_person' => 'required',
            'reference_number' => 'required',
            'files.1' => 'required'
        ]);
});

test('createLetter function expected', function() {
    $user = User::factory()->create();
    $this->actingAs($user);

    // Buat instance komponen Livewire
    $component = new UploadForm();

    // Set property yang dibutuhkan
    $component->title = 'Surat Uji Coba';
    $component->responsible_person = 'John Doe';
    $component->reference_number = 'REF123456';

    // Panggil createLetter
    $letter = $component->createLetter();

    // Pastikan letter tersimpan dengan benar
    expect($letter)->toBeInstanceOf(Letter::class)
        ->and($letter->title)->toBe('Surat Uji Coba')
        ->and($letter->responsible_person)->toBe('John Doe')
        ->and($letter->reference_number)->toBe('REF123456')
        ->and($letter->user_id)->toBe($user->id)
        ->and($letter->letterable_type)->toBe(LetterUpload::class)
        ->and($letter->letterable_id)->toBe(0);
});

test('LetterUpload input expected', function () {

    Storage::fake('public');

    $user = User::factory()->create();
    $this->actingAs($user);

    $files = [
        UploadedFile::fake()->create('file1.pdf', 100, 'application/pdf'),
        UploadedFile::fake()->create('file2.pdf', 100, 'application/pdf'),
        UploadedFile::fake()->create('file3.pdf', 100, 'application/pdf'),
    ];

    Livewire::test(UploadForm::class)
        ->set('title', 'Surat Baru')
        ->set('responsible_person', 'Pak Agus')
        ->set('reference_number', 'SK-2025-01')
        ->set('files', $files)
        ->call('save')
        ->assertHasNoErrors()
        ->assertRedirect('/letter');

    $letter = Letter::first();
    expect($letter)->not->toBeNull();
    expect($letter->uploads()->count())->toBe(3);

    $firstUpload = LetterUpload::where('letter_id', $letter->id)->orderBy('id')->first();
    expect($letter->letterable_id)->toBe($firstUpload->id);

    $statusTrack = RequestStatusTrack::where('letter_id', $letter->id)->first();
    expect($statusTrack)->not->toBeNull();
    expect($statusTrack->created_by)->toBe($user->name);
});

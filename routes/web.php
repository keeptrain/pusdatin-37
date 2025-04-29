<?php

use Livewire\Livewire;
use App\Livewire\Settings\Profile;
use App\Livewire\Admin\ManageUsers;
use App\Livewire\Letters\Chat;
use App\Livewire\Letters\Data\Edit;
use App\Livewire\Settings\Password;
use App\Livewire\Letters\DirectForm;
use App\Livewire\Letters\UploadForm;
use App\Livewire\Letters\Data\Detail;
use App\Livewire\Settings\Appearance;
use Illuminate\Support\Facades\Route;
use App\Livewire\Letters\CreateLetter;
use App\Livewire\Letters\Data\Activity;
use App\Livewire\Letters\Data\ApplicationTable;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::get('letter',CreateLetter::class)->name('letter');
    Route::get('letter/upload',UploadForm::class)->name('letter.upload');
    Route::get('letter/form',DirectForm::class)->name('letter.form');
    Route::get('letter/table',ApplicationTable::class)->name('letter.table');
    // Route::prefix('letter/{id}')->group(function() {
    //     Route::get('detail', [Detail::class])->name('letter.detail');
    //     Route::get('edit', [Edit::class])->name('letter.edit');
    //     Route::get('activity', [Edit::class])->name('letter.activity');
    //     Route::get('chat', [Edit::class])->name('letter.chat');
    //     // etc
    // });
    Route::get('letter/{id}',Detail::class)->name('letter.detail');
    Route::get('letter/{id}/edit', Edit::class)->name('letter.edit');
    Route::get('letter/{id}/activity', Activity::class)->name('letter.activity');
    Route::get('letter/{id}/chat', Chat::class)->name('letter.chat');
});

Route::prefix('admin')->middleware(['auth', 'role:administrator'])->group(function() {
    Route::get('users', ManageUsers::class)->name('admin.users');
    // Route::get('users/{id)/update',UpdateUser::class)->name('admin.users.edit'); 
});

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');
});

require __DIR__.'/auth.php';

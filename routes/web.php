<?php

use App\Livewire\Letters\Chat;
use App\Models\Letters\Letter;
use App\Livewire\Settings\Profile;
use App\Livewire\Admin\ManageUsers;
use App\Livewire\Letters\Data\Edit;
use App\Livewire\Settings\Password;
use App\Livewire\Letters\DirectForm;
use App\Livewire\Letters\UploadForm;
use App\Livewire\Letters\Data\Detail;
use App\Livewire\Settings\Appearance;
use Illuminate\Support\Facades\Route;
use App\Livewire\Letters\CreateLetter;
use App\Livewire\Admin\ManageTemplates;
use App\Livewire\Letters\Data\Activity;
use App\Livewire\Letters\Data\Rollback;
use App\Http\Controllers\DashboardController;
use App\Livewire\Letters\Data\ApplicationTable;
use App\Livewire\Letters\DetailHistory;
use App\Livewire\Letters\HistoryLetter;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::get('letter', CreateLetter::class)->name('letter');
    Route::get('letter/upload', UploadForm::class)->name('letter.upload');
    Route::get('letter/form', DirectForm::class)->name('letter.form');
    Route::get('letter/table', ApplicationTable::class)->name('letter.table');

    Route::get('letter/history', HistoryLetter::class)->name('history');
    Route::get('letter/history/{track}', DetailHistory::class)->name('history.detail');


    Route::get('letter/{id}', Detail::class)->name('letter.detail');
    Route::get('letter/{id}/edit', Edit::class)->name('letter.edit');
    Route::get('letter/{id}/activity', Activity::class)->name('letter.activity');
    Route::get('letter/{id}/chat', Chat::class)->name('letter.chat');
    Route::get('letter/{id}/rollback', Rollback::class)->name('letter.rollback');


    // Route::prefix('letter/{id}')->group(function() {
    //     Route::get('detail', [Detail::class])->name('letter.detail');
    //     Route::get('edit', [Edit::class])->name('letter.edit');
    //     Route::get('activity', [Edit::class])->name('letter.activity');
    //     Route::get('chat', [Edit::class])->name('letter.chat');
    // });
});

Route::group(['middleware' => ['auth', 'role:administrator']], function () {
    Route::prefix('system')->group(function () {
        Route::get('users', ManageUsers::class)->name('manage.users');
        Route::get('templates', ManageTemplates::class)->name('manage.templates');
    });
});

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');
});

require __DIR__ . '/auth.php';

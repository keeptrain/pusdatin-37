<?php

use App\Livewire\Forms\SiDataRequestForm;
use App\Livewire\Letters\Chat;
use App\Livewire\Documents\Review;
use App\Livewire\Settings\Profile;
use App\Livewire\Admin\ManageUsers;
use App\Livewire\Letters\Data\Edit;
use App\Livewire\Settings\Password;
use App\Livewire\Letters\DirectForm;
use App\Livewire\Letters\Data\Detail;
use App\Livewire\Settings\Appearance;
use Illuminate\Support\Facades\Route;
use App\Livewire\Letters\CreateLetter;
use App\Livewire\Letters\Data\Activity;
use App\Livewire\Letters\Data\Rollback;
use App\Livewire\Letters\DetailHistory;
use App\Livewire\Letters\HistoryLetter;
use App\Livewire\Forms\PublicRelationForm;
use App\Http\Controllers\DashboardController;
use App\Livewire\Requests\PublicRelation\Show;
use App\Livewire\Documents\RevisionComparision;
use App\Livewire\Letters\Data\ApplicationTable;
use App\Livewire\Requests\PublicRelation\Index;
use App\Http\Controllers\Admin\TemplateController;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::get('letter', CreateLetter::class)->name('letter');
    Route::get('letter/form', DirectForm::class)->name('letter.form');
    Route::get('letter/table', ApplicationTable::class)->name('letter.table');

    // Information System & Data
    Route::get('form/si-data', SiDataRequestForm::class)->name('si-data.form');

    // Public Relation
    Route::get('form/public-relation', PublicRelationForm::class)->name('pr.form');
    Route::get('public-relation', Index::class)->name('pr.index');
    Route::get('public-relation/{id}', Show::class)->name('pr.show');
    Route::get('public-relation/{id}/activity', \App\Livewire\Requests\PublicRelation\Activity::class)->name('pr.activity');

    Route::get('history', HistoryLetter::class)->name('history');
    Route::get('history/{type}/{id}', DetailHistory::class)->name('history.detail');

    // Information System & Data
    Route::get('letter/{id}', Detail::class)->name('letter.detail');
    Route::get('letter/{id}/edit', Edit::class)->name('letter.edit');
    Route::get('letter/{id}/review', Review::class)->name('letter.review');
    Route::get('letter/{id}/activity', Activity::class)->name('letter.activity');
    Route::get('letter/{id}/chat', Chat::class)->name('letter.chat');
    Route::get('letter/{id}/rollback', Rollback::class)->name('letter.rollback');
    Route::get('letter/{id}/version', RevisionComparision::class)->name('letter.version');

      // Route::get('/letter/{letter}/activity', function (Letter $letter) {
    //     // Controller atau Closure ini akan memuat view yang me-render komponen Livewire
    //     return view('components.user.tracking-list', [
    //         'model' => $letter,
    //         'modelType' => $letter->getMorphClass(),
    //         'modelId' => $letter->id,
    //         'pageTitle' => 'Aktivitas Layanan SI'
    //     ]);
    // })->name('letter.activity');

    // Route::prefix('letter/{id}')->group(function() {
    //     Route::get('detail', [Detail::class])->name('letter.detail');
    //     Route::get('edit', [Edit::class])->name('letter.edit');
    //     Route::get('activity', [Edit::class])->name('letter.activity');
    //     Route::get('chat', [Edit::class])->name('letter.chat');
    // });
});

Route::group(['middleware' => ['auth', 'role:si_verifier|data_verifier|pr_verifier']], function () {
    Route::prefix('system')->group(function () {
        Route::get('users', ManageUsers::class)->name('manage.users');
        Route::get('templates', [TemplateController::class, 'index'])->name('manage.templates');
        Route::get('template/create', [TemplateController::class, 'create'])->name('create.template');
        Route::post('template/store', [TemplateController::class, 'store'])->name('store.template');
        Route::post('template/{typeNumber}', [TemplateController::class, 'download'])->name('download.template');
        // Route::get('templates/create', ManageTemplates::class)->name('template.create');
    });
});

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');
});

require __DIR__ . '/auth.php';

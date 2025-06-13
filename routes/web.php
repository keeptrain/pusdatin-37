<?php

use App\Livewire\Forms\SiDataRequestForm;
use App\Livewire\Letters\Chat;
use App\Livewire\Documents\Review;
use App\Livewire\Settings\Profile;
use App\Livewire\Admin\ManageUsers;
use App\Livewire\Letters\Data\Edit;
use App\Livewire\Settings\Password;
use App\Livewire\Letters\DirectForm;
use App\Livewire\Settings\Appearance;
use Illuminate\Support\Facades\Route;
use App\Livewire\Letters\CreateLetter;
use App\Livewire\Letters\Data\Rollback;
use App\Livewire\Letters\DetailHistory;
use App\Livewire\Letters\HistoryLetter;
use App\Livewire\Forms\PublicRelationForm;
use App\Http\Controllers\DashboardController;
use App\Livewire\Requests\PublicRelation\Show;
use App\Livewire\Documents\RevisionComparision;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\ExportPdf\DataVerifierPdfExportController;
use App\Http\Controllers\ExportPdf\HeadVerifierPdfExportController;
use App\Http\Controllers\ExportPdf\PrVerifierPdfExportController;
use App\Http\Controllers\ExportPdf\SiVerifierPdfExportController;
use App\Livewire\Admin\Analytic;
use App\Livewire\Documents\Template;
use App\Livewire\Requests\InformationSystem\Meeting;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::get('letter', CreateLetter::class)->name('letter');
    Route::get('letter/form', DirectForm::class)->name('letter.form');

    // Information System & Data
    Route::get('form/si-data', SiDataRequestForm::class)->name('si-data.form');

    // Information System & Data
    Route::get('information-system', \App\Livewire\Requests\InformationSystem\Index::class)->name('is.index');
    Route::get('information-system/{id}', \App\Livewire\Requests\InformationSystem\Show::class)->name('is.show');
    Route::get('information-system/{id}/activity', \App\Livewire\Requests\InformationSystem\Activity::class)->name('is.activity');
    Route::get('information-system/{id}/meeting', Meeting::class)->name('is.meeting');

    Route::get('information-system/{id}/version', RevisionComparision::class)->name('comparison.version');

    // Public Relation
    Route::get('form/public-relation', PublicRelationForm::class)->name('pr.form');
    Route::get('public-relation', \App\Livewire\Requests\PublicRelation\Index::class)->name('pr.index');
    Route::get('public-relation/{id}', Show::class)->name('pr.show');
    Route::get('public-relation/{id}/activity', \App\Livewire\Requests\PublicRelation\Activity::class)->name('pr.activity');

    Route::get('history', HistoryLetter::class)->name('history');
    Route::get('history/{type}/{id}', DetailHistory::class)->name('history.detail');

    Route::get('letter/{id}/edit', Edit::class)->name('letter.edit');
    Route::get('letter/{id}/review', Review::class)->name('letter.review');
    Route::get('letter/{id}/chat', Chat::class)->name('letter.chat');
    Route::get('letter/{id}/rollback', Rollback::class)->name('letter.rollback');


    // analytic
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

Route::group(['middleware' => ['auth', 'role:administrator|si_verifier|data_verifier|pr_verifier|head_verifier']], function () {
    Route::prefix('system')->group(function () {
        Route::get('users', ManageUsers::class)->name('manage.users');
        Route::get('templates', Template::class)->name('manage.templates');
        // Route::get('template/create', [TemplateController::class, 'create'])->name('create.template');
        // Route::post('template/store', [TemplateController::class, 'store'])->name('store.template');
        // Route::put('template/update/{id}', [TemplateController::class, 'update'])->name('update.template');
        // Route::post('template/{typeNumber}', [TemplateController::class, 'download'])->name('download.template');
        // Route::get('templates/create', ManageTemplates::class)->name('template.create');

        // head export route
        Route::get('analytic', Analytic::class, 'index')->name('analytic.index');
        Route::get('/export/head-verifier', [ExportController::class, 'exportHeadVerifier'])->name('export.head_verifier');
        Route::get('/export/head-verifier-filtered', [ExportController::class, 'exportHeadVerifierFilteredExcel'])
            ->name('head_verifier-filter-excel');
        Route::get('/export/head-verifier-pdf', [HeadVerifierPdfExportController::class, 'export'])
            ->name('export.head_verifier.pdf');
        Route::get('/export/head-verifier-filtered-pdf', [HeadVerifierPdfExportController::class, 'exportFiltered'])
            ->name('head-filtered-pdf');

        //si export route
        Route::get('/export/si-verifier', [ExportController::class, 'exportSiVerifier'])->name('export.si_verifier');
        Route::get('/export/si-verifier-filtered', [ExportController::class, 'exportSiVerifierWithFilter'])
            ->name('si_verifier-filter-excel');
        Route::get('/export/si-verifier-pdf', [SiVerifierPdfExportController::class, 'export'])->name('export.si_verifier.pdf');
        Route::get('/export/si-verifier-filtered-pdf', [SiVerifierPdfExportController::class, 'exportFiltered'])->name('si-filtered-pdf');

        // data export route
        Route::get('/export/data-verifier', [ExportController::class, 'exportDataVerifier'])->name('export.data_verifier');
        Route::get('/export/data-verifier-filtered', [ExportController::class, 'exportDataVerifierWithFilter'])
            ->name('data_verifier-filter-excel');
        Route::get('/export/data-verifier-pdf', [DataVerifierPdfExportController::class, 'export'])->name('export.data_verifier.pdf');
        Route::get('/export/data-verifier-filtered-pdf', [DataVerifierPdfExportController::class, 'exportFiltered'])->name('data-filtered-pdf');

        // pr export route
        Route::get('/export/pr-verifier', [ExportController::class, 'exportPrVerifier'])->name('export.pr_verifier');
        Route::get('/export/pr-verifier-filtered', [ExportController::class, 'exportPrVerifierWithFilter'])
            ->name('pr_verifier-filter-excel');
        Route::get('/export/pr-verifier-pdf', [PrVerifierPdfExportController::class, 'export'])->name('export.pr_verifier.pdf');
        Route::get('/export/pr-verifier-filtered-pdf', [PrVerifierPdfExportController::class, 'exportFiltered'])
            ->name('pr-filtered-pdf');
    });
});

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');
});

require __DIR__ . '/auth.php';

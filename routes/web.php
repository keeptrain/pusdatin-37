<?php

use App\Livewire\Documents\Review;
use App\Livewire\Settings\Profile;
use App\Livewire\Admin\ManageUsers;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Appearance;
use App\Mail\Requests\InformationSystem\NewMeeting;
use Illuminate\Support\Facades\Route;
use App\Livewire\Forms\PublicRelationForm;
use App\Http\Controllers\DashboardController;
use App\Livewire\Requests\PublicRelation\Show;
use App\Livewire\Documents\RevisionComparision;
use App\Livewire\Admin\Analytic;
use App\Livewire\Documents\ManageTemplate;
use App\Livewire\Requests\InformationSystem\Meeting;
use App\Livewire\Requests\InformationSystem\Edit;
use App\Livewire\Forms\SiDataRequestForm;
use App\Livewire\Requests\ShowRatings;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\ExportPdf\DataVerifierPdfExportController;
use App\Http\Controllers\ExportPdf\HeadVerifierPdfExportController;
use App\Http\Controllers\ExportPdf\PrVerifierPdfExportController;
use App\Http\Controllers\ExportPdf\SiVerifierPdfExportController;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Route::get('/test-email', function () {
//     try {
//         Mail::to('remajamesjid1945@gmail.com')->send(new RevisionMail());
//         return "Email sedang dikirim!";
//     } catch (\Exception $e) {
//         return "Gagal mengirim email: " . $e->getMessage();
//     }
// });

// Route::get('/test-email', fn($data) => new RevisionMail($data));
Route::get('/test-email', function () {
    $data = [
        'topic' => 'Test topic',
        'title' => 'Test title',
        'date' => '2025-06-25',
        'start' => '10:00',
        'end' => '12:00',
        'name' => 'Gilang',
        'link' => 'https://google.com',
        'password' => 'test notesop',
    ];
    return new NewMeeting($data, 'create');
});

Route::middleware(['auth'])->group(function () {
    // Information System & Data
    Route::get('/download-sop-and-templates', [DashboardController::class, 'downloadSopAndTemplates'])->name('download.sop-and-templates')->middleware('role:user');
    Route::get('form/si-data', SiDataRequestForm::class)->name('si-data.form')->middleware('can:create request');
    Route::get('information-system/{id}/edit', Edit::class)->name('is.edit')->middleware('can:revision si-data request');

    Route::middleware('permission:view si request|view data request')->group(function () {
        Route::get('information-system', \App\Livewire\Requests\InformationSystem\Index::class)->name('is.index');
        Route::get('information-system/{id}', \App\Livewire\Requests\InformationSystem\Show::class)->name('is.show');
        Route::get('information-system/{id}/activity', \App\Livewire\Requests\InformationSystem\Activity::class)->name('is.activity');
        Route::get('information-system/{id}/meeting', Meeting::class)->name('is.meeting');
        Route::get('information-system/{id}/version', RevisionComparision::class)->name('comparison.version');
        Route::get('information-system/{id}/rollback', \App\Livewire\Requests\InformationSystem\Rollback::class)->name('is.rollback');
        Route::get('information-system/{id}/review', Review::class)->name('is.review');
    });

    Route::get('request/{id}/chat', \App\Livewire\Requests\Chat::class)->name('request.chat');
    Route::get('ratings', ShowRatings::class)->name('show.ratings');

    // Public Relation
    Route::get('form/public-relation', PublicRelationForm::class)->name('pr.form')->middleware('can:create request');

    Route::middleware('can:view pr request')->group(function () {
        Route::get('public-relation', \App\Livewire\Requests\PublicRelation\Index::class)->name('pr.index');
        Route::get('public-relation/{id}', Show::class)->name('pr.show');
        Route::get('public-relation/{id}/activity', \App\Livewire\Requests\PublicRelation\Activity::class)->name('pr.activity');
        Route::get('public-relation/{id}/rollback', \App\Livewire\Requests\PublicRelation\Rollback::class)->name('pr.rollback');
    });

    Route::middleware('can:view requests')->group(function () {
        Route::get('permohonan', \App\Livewire\Requests\User\ListRequest::class)->name('list.request');
        Route::get('permohonan/{type}/{id}', \App\Livewire\Requests\User\Detail::class)->name('detail.request');
    });
});

Route::group(['middleware' => ['auth', 'role:administrator|si_verifier|data_verifier|pr_verifier|head_verifier']], function () {
    Route::prefix('system')->group(function () {
        Route::get('users', ManageUsers::class)->name('manage.users');
        Route::get('users/{id}', \App\Livewire\Admin\User\Show::class)->name('user.show');
        Route::get('templates', ManageTemplate::class)->name('manage.templates');
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

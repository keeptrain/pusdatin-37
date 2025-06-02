<?php

namespace App\Http\Controllers\ExportPdf;

use App\Models\Letters\Letter;
use Barryvdh\DomPDF\Facade\Pdf;

class DataVerifierPdfExportController extends \App\Http\Controllers\Controller
{
    public function export()
    {

        $letters = Letter::with('user')->where('current_division', 4)->get();

        $data = [
            'letters' => $letters,
        ];

        $pdf = Pdf::loadView('pdf.data_verifier_report', $data);

        return $pdf->download('data_verifier_report.pdf');
    }
}
